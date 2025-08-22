<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Notice;
use DB;
use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Addon;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Service;
use App\Models\Customer;
use App\Models\OrderRate;
use App\Models\OrderAddon;
use App\Models\BankAccount;
use App\Models\InvoiceLink;
use App\Models\TermsSittng;
use Illuminate\Http\Request;
use App\Models\PreLoginImage;
use App\Models\ServiceReport;
use App\Models\PreLogoutImage;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\OrderItem;
use App\Repositories\IUserRepository;
use App\Repositories\IOrderRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ICategoryRepository;
use Illuminate\Support\Facades\DB as FacadesDB;

class OrderController extends Controller
{
    private $orderRepository;

    public function __construct(
        IOrderRepository $orderRepository,
        IUserRepository $Iuser,
        ICategoryRepository $Icat
    ) {

        $this->orderRepository = $orderRepository;
        $this->Iuser = $Iuser;
        $this->Icat = $Icat;
    }

    public function index()
    {
        $validStatuses = ['completed', 'rejected', 'canceled', 'delayed'];
        $status = request()->query('status');
        $customerId = request()->query('customer_id');

        // price filters
        $filters = [
            'price_min' => request()->query('price_min'),
            'price_max' => request()->query('price_max'),
            'price' => request()->query('price'),
        ];

        $query = Order::query();

        if (in_array($status, $validStatuses)) {
            $query->where('status', $status);
        } elseif ($status == 'pending') {
            $query->where(fn($q) => $q->whereIn('status', ['pending_and_show_price', 'pending_and_Initial_reservation']));
        } else if ($status == 'approved') {
            $query->where(fn($q) => $q->whereIn('status', ['approved', 'delayed']));
        }

        if (!empty($customerId)) {
            $query->where('customer_id', $customerId);
        }

        $orders = $query->filter($filters)
            ->orderBy('created_at', 'desc')
            ->with(['payments', 'addons'])
            ->paginate(100);

        return view('dashboard.orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::select('id', 'name')->get();
        $services = Service::select('id', 'name', 'price')->get();

        return view('dashboard.orders.create', [
            'customers' => $customers,
            'services' => $services,
        ]);
    }

    public function updateInsurance(Request $request, $orderId)
    {
        $validatedData = $request->validate([
            'insurance_status' => 'nullable|in:returned,confiscated_full,confiscated_partial',
            'confiscation_description' => 'nullable|string',
            'partial_confiscation_amount' => 'nullable|numeric|min:0',
        ]);

        $order = Order::findOrFail($orderId);
        $originalInsuranceAmount = $order->insurance_amount;
        $price = $order->price;

        if ($originalInsuranceAmount <= 0) {
            return back()->withErrors(['insurance_amount' => 'لا يمكن تنفيذ العملية لان مبلغ التامين = 0 ']);
        }

        if ($validatedData['insurance_status'] === 'confiscated_partial') {
            $insuranceAmount = $request->input('partial_confiscation_amount', 0);

            $insuranceAmounts = $originalInsuranceAmount - $insuranceAmount;
            $price += $insuranceAmount;
        } elseif ($validatedData['insurance_status'] === 'confiscated_full') {
            $insuranceAmounts = 0;
        } elseif ($validatedData['insurance_status'] === 'returned') {
            $insuranceAmounts = 0;
        } else {
            $insuranceAmounts = $originalInsuranceAmount;
            $price = $originalInsuranceAmount;
        }

        $order->update([
            'insurance_status' => $validatedData['insurance_status'],
            'confiscation_description' => $validatedData['confiscation_description'],
            'insurance_amount' => $insuranceAmounts,
            'price' => $price,
        ]);

        return back()->with('success', __('dashboard.success'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
            'price' => 'required',
            'deposit' => 'nullable',
            'insurance_amount' => 'required',
            'notes' => 'nullable|string',
            'date' => 'nullable|date',
            'time_from' => 'nullable',
            'time_to' => 'nullable',
            'time_of_receipt' => 'nullable',
            'image_before_receiving' => 'nullable',
            'delivery_time' => 'nullable',
            'image_after_delivery' => 'nullable',
            'status' => 'required|in:pending_and_show_price,pending_and_Initial_reservation,approved,canceled,delayed,completed',
            'expired_price_offer' => 'required_if:status,pending_and_show_price,pending_and_Initial_reservation',
            'created_by' => 'required|exists:users,id',
            'agree' => 'nullable|in:1,0',
            'delayed_reson' => 'nullable|string',
            'refunds' => 'nullable|in:1,0',
            'refunds_notes' => 'nullable',
            'delayed_time' => 'nullable',
            'people_count' => 'required|integer|min:1',
            'client_notes' => 'nullable|string'
        ]);

        $validatedData['inventory_withdrawal'] = isset($request->inventory_withdrawal) ? '1' : '0';
        unset($validatedData['service_ids']);
        $validatedData['price'] = $request->price;
        $existingOrders = Order::where('date', $request->date)
            ->whereHas('services', function ($query) use ($request) {
                $query->whereIn('service_id', $request->service_ids);
            })
            ->exists();

        if ($existingOrders) {
            return response()->json(['error' => __('dashboard.has already been served the selected services on this date')], 400);
        }

        if ($request->filled('time_from') && $request->filled('time_to')) {
            $timeFrom = Carbon::parse($request->time_from)->format('H:i:s');
            $timeTo = Carbon::parse($request->time_to)->format('H:i:s');

            if ($timeFrom > $timeTo) {
                $timeTo = '24:00:00';
            }

            $validatedData['time_from'] = $timeFrom;
            $validatedData['time_to'] = $timeTo;
        }

        $order = Order::create($validatedData);

        $servicesData = [];
        foreach ($request->service_ids as $index => $serviceId) {
            $servicesData[$serviceId] = ['price' => Service::findOrFail($serviceId)->price];
        }

        // Attach services to the order with their prices
        $order->services()->attach($servicesData);

        // Handle inventory withdrawal if selected
        if ($order->inventory_withdrawal == '1') {
            $this->handleInventoryWithdrawal($order);
        }

        return response()->json();
    }

    //  Calculates the number of hours between the start and end times.
    public function addHoursCount()
    {
        // Check if both time_from and time_to are set
        if (!$this->time_from || !$this->time_to) {
            return 0; // Return 0 if either time is missing
        }

        // Parse the time_from and time_to values into Carbon instances
        $timeFrom = Carbon::parse($this->time_from);
        $timeTo = Carbon::parse($this->time_to);

        // Handle special case where time_to is '24:00:00' or '00:00:00'
        if ($this->time_to === '24:00:00' || $timeTo->format('H:i:s') === '00:00:00') {
            // Set time_to to the start of the next day
            $timeTo = $timeFrom->copy()->addDay()->startOfDay();
        }
        // Handle case where time_to is earlier than time_from (indicating overnight time span)
        elseif ($timeTo->lt($timeFrom)) {
            // Add one day to time_to to correctly calculate the difference
            $timeTo->addDay();
        }

        // Calculate the difference in hours between time_from and time_to
        $diffInHours = $timeFrom->diffInHours($timeTo);

        // Return the calculated difference in hours
        return $diffInHours;
    }

    public function edit($id)
    {
        $order = $this->orderRepository->findOne($id);
        $customers = Customer::select('id', 'name')->get();
        $services = Service::select('id', 'name', 'price')->get();
        $addonsPrice = OrderAddon::where('order_id', $order->id)->sum('price');

        return view('dashboard.orders.create', compact('order', 'customers', 'services', 'addonsPrice'));
    }

    public function insurance($id)
    {
        $order = $this->orderRepository->findOne($id);
        return view('dashboard.orders.insurance', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::with('services.stocks')->findOrFail($id);
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
            'price' => 'required|numeric',
            'deposit' => 'nullable|numeric',
            'insurance_amount' => 'required|numeric',
            'notes' => 'nullable|string',
            'date' => 'nullable|date',
            'time_from' => 'nullable',
            'time_to' => 'nullable',
            'expired_price_offer' => 'required_if:status,pending_and_show_price,pending_and_Initial_reservation',
            'time_of_receipt' => 'nullable',
            'image_before_receiving' => 'nullable|image',
            'delivery_time' => 'nullable',
            'image_after_delivery' => 'nullable|image',
            'status' => 'required|in:pending_and_show_price,pending_and_Initial_reservation,approved,canceled,delayed,completed',
            'delayed_reson' => 'nullable',
            'agree' => 'nullable|in:1,0',
            'refunds' => 'nullable|in:1,0',
            'refunds_notes' => 'nullable',
            'delayed_time' => 'nullable',
            'people_count' => 'required|integer|min:1',
            'client_notes' => 'nullable|string'
        ]);

        $validatedData['inventory_withdrawal'] = $request->has('inventory_withdrawal') ? '1' : '0';
        \DB::beginTransaction();

        try {
            unset($validatedData['service_ids']);

            $order->fill($validatedData);

            $totalPrice = 0;
            foreach ($request->service_ids as $serviceId) {
                $service = Service::findOrFail($serviceId);
                $totalPrice += $service->price;
            }
            $order->price = $totalPrice;
            $service_ids = array_map('intval', $request->service_ids);
            \DB::table('order_service')->where('order_id', $order->id)->delete();

            foreach ($service_ids as $serviceId) {
                \DB::table('order_service')->insert([
                    'order_id' => $order->id,
                    'service_id' => $serviceId,
                    'price' => Service::findOrFail($serviceId)->price
                ]);
            }

            $order->save();
            \DB::commit();

            return response()->json(['message' => 'Order updated successfully']);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Order update failed: ' . $e->getMessage());
            return redirect()->route('orders.edit', $order->id)->with('error', 'Error updating order');
        }
    }


    public function destroy($id)
    {
        $this->orderRepository->forceDelete($id);
        return response()->json();
    }

    public function show($id)
    {
        try {
            // Optional: Check if the provided ID is numeric
            if (!is_numeric($id)) {
                return redirect()->route('orders.index')->with('error', __('dashboard.invalid_order_id'));
            }

            $this->authorize('view', Order::class); // Authorization check

            $order = Order::with(['payments', 'expenses'])->findOrFail($id);
            return view('dashboard.orders.show', compact('order'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('orders.index')->with('error', __('dashboard.order_not_found'));
        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', __('dashboard.something_went_wrong'));
        }
    }

    public function reports($id)
    {
        $order = Order::findOrFail($id);
        $reports = ServiceReport::whereIn('service_id', $order->services->pluck('id')->toArray())->get();

        $service = Service::findOrFail($order->services->pluck('id')->first());
        $stocks = Stock::all();
        return view('dashboard.orders.reports', compact('order', 'reports', 'service', 'stocks'));
    }

    public function storeAddons(Request $request, $orderId)
    {
        $validatedData = $request->validate([
            'addon_id' => 'required|exists:addons,id',
            'count' => 'nullable|integer|min:1',
            'account_id' => 'nullable|exists:bank_accounts,id',
            'price' => 'required|numeric',
            'payment_method' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        $order = Order::findOrFail($orderId);
        $order->addons()->attach($validatedData['addon_id'], [
            'count' => $validatedData['count'],
            'price' => $validatedData['price'],
            'payment_method' => $validatedData['payment_method'] ?? null,
            'account_id' => $validatedData['account_id'] ?? null,
            'description' => $validatedData['description'] ?? '',
        ]);
        $order->update([
            'price' => $order->price
        ]);
        return back()->with('success', __('dashboard.success'));
    }

    public function updateAddons(Request $request, $pivotId)
    {
        $validatedData = $request->validate([
            'addon_id' => 'required|exists:addons,id',
            'count' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'account_id' => 'nullable|exists:bank_accounts,id',
            'payment_method' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        \DB::table('order_addon')->where('id', $pivotId)->update([
            'addon_id' => $validatedData['addon_id'],
            'count' => $validatedData['count'],
            'account_id' => $validatedData['account_id'] ?? null,
            'payment_method' => $validatedData['payment_method'] ?? null,
            'price' => $validatedData['price'],
            'description' => $validatedData['description'] ?? '',
        ]);
        return back()->with('success', __('dashboard.success'));
    }

    public function removeAddon($pivotId)
    {
        $existingAddon = DB::table('order_addon')->where('id', $pivotId)->first();
        $order = Order::findOrFail($existingAddon->order_id);

        if (!$existingAddon) {
            return back()->with('error', __('dashboard.addon_not_found'));
        }

        // Calculate the price difference
        $priceDifference = $existingAddon->price;

        // Delete the pivot record
        DB::table('order_addon')->where('id', $pivotId)->delete();

        // Update order price
        $order->update([
            'price' => $order->price - $priceDifference
        ]);

        return back()->with('success', __('dashboard.success'));
    }

    public function addons($id)
    {
        $order = Order::with('addons')->findOrFail($id);
        $addons = Addon::all();
        $bankAccounts = BankAccount::all();

        return view('dashboard.orders.addons', compact('order', 'addons', 'bankAccounts'));
    }

    public function updateReports(Request $request, $id)
    {
        $this->updateStock($request->all());
        $order = Order::with('reports')->findOrFail($id);
        $data = $request->validate([
            'ordered_count.*' => 'nullable|numeric|min:1',
            'ordered_price.*' => 'nullable|numeric|min:1',
            'count'   => 'required|array',
            'count.*' => 'nullable|integer|min:0|max:100',
            'not_completed_reason' => 'nullable|array',
            'reports' => 'nullable|array',
        ]);

        foreach ($data['reports'] as $id => $status) {
            $report = ServiceReport::find($id);
            if ($report) {
                $report->update([
                    'is_completed' => ($status === 'completed') ? true : false,
                    'not_completed_reason' => $data['not_completed_reason'][$id] ?? null,
                    'count' => $data['count'][$id] ?? 0,
                ]);
            }
        }

        $reports = $request->input('reports', []);
        $reportsNot = $request->input('reports_not', []);
        $notCompletedReasons = $request->input('not_completed_reason', []);
        $orderedCounts = $request->input('ordered_count', []);
        $ordered_price = $request->input('ordered_price', []);

        foreach ($reports as $reportId => $status) {
            $orderReport = $order->reports()->firstOrNew(['service_report_id' => $reportId]);
            $orderReport->is_completed = 'completed';
            $orderReport->not_completed_reason = null;

            if (isset($orderedCounts[$reportId])) {
                $orderReport->ordered_count = $orderedCounts[$reportId];
                $orderReport->ordered_price = $ordered_price[$reportId];
            }

            $orderReport->save();
        }

        foreach ($reportsNot as $reportId => $status) {
            $orderReport = $order->reports()->firstOrNew(['service_report_id' => $reportId]);
            $orderReport->is_completed = 'not_completed';
            $orderReport->not_completed_reason = $notCompletedReasons[$reportId] ?? null;

            if (isset($orderedCounts[$reportId])) {
                $orderReport->ordered_count = $orderedCounts[$reportId];
            }

            $orderReport->save();
        }

        $existingReportIds = array_merge(array_keys($reports), array_keys($reportsNot));
        foreach ($order->reports as $report) {
            if (!in_array($report->service_report_id, $existingReportIds)) {
                $report->is_completed = 'no_action';
                $report->not_completed_reason = null;

                if (isset($orderedCounts[$report->service_report_id])) {
                    $report->ordered_count = $orderedCounts[$report->service_report_id];
                    $report->ordered_price = $ordered_price[$report->service_report_id];
                }

                $report->save();
            }
        }

        $order->update([
            'report_text' => $request->report_text
        ]);

        return back()->withSuccess(__('dashboard.success'));
    }

    private function updateStock(array $data): void
    {
        if (isset($data['stock'])) {
            foreach ($data['stock'] as $stockId => $status) {
                $pivot = FacadesDB::table('service_stock')->whereId($stockId);
                $pivot->update([
                    'is_completed' => ($status === 'completed') ? true : false,
                    'not_completed_reason' => $data['not_completed_reason_stock'][$stockId] ?? null,
                    'required_qty' => $data['required_qty_stock'][$stockId] ?? null,
                    'count' => $data['count_stock'][$stockId] ?? null,
                ]);
            }
        }
    }

    public function decrmentOrIncrementStock(Request $request)
    {
        $data = $request->validate([
            'id'      => 'required|integer|exists:service_stock,id',
            'stockId' => 'required|integer|exists:stocks,id',
            'qty'     => 'required|integer|min:1',
            'status'  => 'required|in:decrement,increment',
        ]);

        try {
            $result = FacadesDB::transaction(function () use ($data) {
                $affected = FacadesDB::table('service_stock')
                    ->where('id', $data['id'])
                    ->where('stock_id', $data['stockId'])
                    ->update([
                        'required_qty' => $data['qty'],
                        'updated_at'   => now(),
                    ]);

                if ($affected === 0) abort(404, 'Pivot not found for this stock.');

                $stock = Stock::whereKey($data['stockId'])->lockForUpdate()->firstOrFail();
                if ($data['status'] === 'increment') {
                    $stock->increment('quantity' , $data['qty']);
                } elseif ($data['status'] === 'decrement') {
                    if ($data['qty'] > $stock->quantity) abort(422, __('dashboard.insufficient_stock'));
                    $stock->decrement('quantity' , $data['qty']);
                } else {
                    abort(422, __('dashboard.not_found'));
                }

                return [
                    'remaining' => (int) $stock->quantity,
                    'decrement' => (int) $data['qty'],
                    'stock_id'  => (int) $stock->id,
                    'pivot_id'  => (int) $data['id'],
                ];
            });

            return response()->json(['ok' => true, 'data' => $result], 200);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function invoice($order)
    {
        $order = Order::with(['payments'])->findOrFail($order);
        $html = view('dashboard.orders.invoice', compact('order'))->render();

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('invoice.pdf', 'I');
    }
    public function receipt($order)
    {
        $order = Order::with('payments')->findOrFail($order);
        $html = view('dashboard.orders.receipt', compact('order'))->render();

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('receipt.pdf', 'I');
    }

    public function getInvoiceByLink($link)
    {
        $invoiceLink = InvoiceLink::where('link', $link)->firstOrFail();
        $order = Order::with('payments', 'services')->findOrFail($invoiceLink->order_id);
        $termsSittng = TermsSittng::firstOrFail();
        $html = view('dashboard.orders.pdf', compact('termsSittng', 'order'))->render();
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('terms.pdf', 'I');
    }

    public function quote($order)
    {
        $order = Order::with(['payments', 'addons'])->findOrFail($order);
        $html = view('dashboard.orders.quote', compact('order'))->render();
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('invoice.pdf', 'I');
    }

    public function signin($id)
    {
        $order = Order::findOrFail($id);
        return view('dashboard.orders.signin', compact('order'));
    }

    public function logout($id)
    {
        $order = Order::findOrFail($id);
        return view('dashboard.orders.logout', compact('order'));
    }

    public function rate_orders()
    {
        //        $orders = Order::where('status', 'completed')->get()
        $rates = OrderRate::paginate(10);
        return view('dashboard.orders.rate_orders', compact('rates'));
    }

    public function updatesignin(Request $request, $orderId)
    {
        $validatedData = $request->validate([
            'time_of_receipt' => 'nullable',
            'time_of_receipt_notes' => 'nullable|string',
            'delivery_time' => 'nullable',
            'delivery_time_notes' => 'nullable|string',
            'voice_note' => 'nullable|file',
            'video_note' => 'nullable|file', // تأكد من إضافة هذا السطر
            'delete_voice_note' => 'nullable|boolean',
            'delete_video_note' => 'nullable|boolean',
            'voice_note_logout' => 'nullable|file',
            'video_note_logout' => 'nullable|file',

        ]);

        $order = Order::findOrFail($orderId);

        // معالجة صوتيات
        if ($request->delete_voice_note) {
            $order->voice_note = null;
        } elseif ($request->hasFile('voice_note')) {
            $order->voice_note = $request->file('voice_note')->store('voice_notes');
        } elseif ($request->hasFile('voice_note_logout')) {
            $order->voice_note_logout = $request->file('voice_note_logout')->store('voice_notes');
        } elseif ($request->delete_voice_note_logout) {
            if ($order->voice_note_logout) {
                Storage::disk('public')->delete($order->voice_note_logout);
            }
            $order->voice_note_logout = null;
        }

        // معالجة الفيديو
        if ($request->delete_video_note) {
            $order->video_note = null; // Clear the video note
        } elseif ($request->hasFile('video_note')) {
            $order->video_note = $request->file('video_note')->store('video_notes');
        } elseif ($request->hasFile('video_note_logout')) {
            $order->video_note_logout = $request->file('video_note_logout')->store('video_notes');
        } elseif ($request->delete_video_note_logout) {
            if ($order->video_note_logout) {
                Storage::disk('public')->delete($order->video_note_logout);
            }
            $order->video_note_logout = null;
        }

        // تحديث تفاصيل الطلب الأخرى
        $order->update([
            'time_of_receipt' => $request->time_of_receipt ?: $order->time_of_receipt,
            'time_of_receipt_notes' => $request->time_of_receipt_notes ?: $order->time_of_receipt_notes,
            'delivery_time' => $request->delivery_time ?: $order->delivery_time,
            'delivery_time_notes' => $request->delivery_time_notes ?: $order->delivery_time_notes,
        ]);

        return redirect()->back()->with('success', __('dashboard.success'));
    }


    public function uploadTemporaryImage(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'pre_login_image' => 'required|array',
            'pre_login_image.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $uploadedFiles = [];

        foreach ($request->file('pre_login_image') as $file) {
            if ($request->type && $request->type == 'logout') {
                $preLoginImage = PreLogoutImage::create([
                    'order_id' => $request->order_id,
                    'image' => $file
                ]);
            } else {
                $preLoginImage = PreLoginImage::create([
                    'order_id' => $request->order_id,
                    'image' => $file
                ]);
            }

            $uploadedFiles[] = [
                'filePath' => $preLoginImage->image,
                'id' => $preLoginImage->id
            ];
        }

        return response()->json($uploadedFiles, 200);
    }


    public function removeImage($id)
    {
        if (request()->type == 'logout') {
            $image = PreLogoutImage::findOrFail($id);
        } else {
            $image = PreLoginImage::findOrFail($id);
        }

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return response()->json(['success' => true], 200);
    }


    public function deleteAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (Order::whereIn('id', $ids)->delete()) {
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }

    public function checkCustomerNotices($customerId)
    {
        $notices = Notice::where('customer_id', $customerId)
            ->with('creator')
            ->latest()
            ->get();

        return response()->json([
            'hasNotices' => $notices->isNotEmpty(),
            'notices' => $notices->map(function ($notice) {
                return [
                    'content' => $notice->notice,
                    'created_at' => $notice->created_at->format('Y-m-d H:i'),
                    'created_by' => $notice->creator->name
                ];
            })
        ]);
    }

    public function updateVerified(int $id, string $type)
    {
        try {
            \DB::beginTransaction();

            if ($type == 'addon') {
                $item = OrderAddon::findOrFail($id);
            } elseif ($type == 'expense') {
                $item = Expense::findOrFail($id);
            } elseif ($type == 'warehouse_sales') {
                $item = OrderItem::findOrFail($id);
            } else {
                return redirect()->back()->with('error', __('dashboard.invalid_type'));
            }

            $item->verified = !$item->verified;
            $item->save();

            \DB::commit();
            return redirect()->back()->with('success', __('dashboard.success'));
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function acceptTerms($id)
    {
        return view('dashboard.orders.accept_terms', ['order' => Order::findOrFail($id), 'termsSittng' => TermsSittng::firstOrFail()]);
    }

    public function updateNotes(Request $request, Order $order)
    {
        $data = $request->validate([
            'terms_notes' => ['nullable', 'string', 'max:20000'],
        ]);

        $order->update($data);

        return back()->with('success', __('dashboard.success'));
    }
}
