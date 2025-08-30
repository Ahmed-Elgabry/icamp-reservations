<?php

namespace App\Http\Controllers\Dashboard;

use App\Mail\OrderDocumentsMail;
use App\Models\Notice;
use App\Models\Payment;
use DB;
use Endroid\QrCode\Color\Color;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
//use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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
        $this->authorize('viewAny', Order::class);

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
            ->withSum(['payments as verified_payments_sum' => function ($q) {
                $q->verified();
            }], 'price')
            ->paginate(100);

        return view('dashboard.orders.index', compact('orders'));
    }

    public function create()
    {
        $this->authorize('create', Order::class);

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
        $this->authorize('create', Order::class);

        $validatedData = $request->validate([
            'customer_id' => 'required',
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
            'show_price_notes' => 'nullable|string',
            'order_data_notes' => 'nullable|string',
            'invoice_notes' => 'nullable|string',
            'receipt_notes' => 'nullable|string',
            'people_count' => 'required|integer|min:1',
            'client_notes' => 'nullable|string'

        ]);

        $validatedData['inventory_withdrawal'] = isset($request->inventory_withdrawal) ? '1' : '0';
        unset($validatedData['service_ids']);
        $validatedData['price'] = $request->price;


        $validatedData['show_price_notes'] = $request->show_price_notes;
        $validatedData['order_data_notes'] = $request->order_data_notes;
        $validatedData['invoice_notes'] = $request->invoice_notes;
        $validatedData['receipt_notes'] = $request->receipt_notes;

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

        $order->services()->attach($servicesData);
        if ($order->inventory_withdrawal == '1') {
            $this->handleInventoryWithdrawal($order);
        }

        return response()->json();
    }

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
        $this->authorize('update', $order);
        $customers = Customer::select('id', 'name')->get();
        $services = Service::select('id', 'name', 'price')->get();
        $addonsPrice = OrderAddon::where('order_id', $order->id)->sum('price');

        $additionalNotesData = [
            'notes' => $order->additional_notes,
            'show_price' => $order->show_price,
            'order_data' => $order->order_data,
            'invoice' => $order->invoice,
            'receipt' => $order->receipt
        ];
        return view('dashboard.orders.create', compact('order', 'customers', 'services', 'addonsPrice', 'additionalNotesData'));
    }

    public function insurance($id)
    {
        $order = $this->orderRepository->findOne($id);
        return view('dashboard.orders.insurance', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::with('services.stocks')->findOrFail($id);
        $this->authorize('update', $order);
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
            'show_price_notes' => 'nullable|string',
            'order_data_notes' => 'nullable|string',
            'invoice_notes' => 'nullable|string',
            'receipt_notes' => 'nullable|string',
            'people_count' => 'required|integer|min:1',
            'client_notes' => 'nullable|string'
        ]);

        $validatedData['inventory_withdrawal'] = $request->has('inventory_withdrawal') ? '1' : '0';
        \DB::beginTransaction();

        try {
            unset($validatedData['service_ids']);

            $validatedData['show_price_notes'] = $request->show_price_notes;
            $validatedData['order_data_notes'] = $request->order_data_notes;
            $validatedData['invoice_notes'] = $request->invoice_notes;
            $validatedData['receipt_notes'] = $request->receipt_notes;

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
        $order = $this->orderRepository->findOne($id);
        $this->authorize('delete', $order);
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

            $order = Order::with(['payments', 'expenses'])->findOrFail($id);
            $this->authorize('view', $order); // Authorization check
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
        $this->authorize('reports', $order);
        $reports = ServiceReport::whereIn('service_id', $order->services->pluck('id')->toArray())
            ->orderBy('ordered_count')
            ->get();

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
        $data = $request->validate([
            'ordered_count.*' => 'nullable|numeric|min:1',
            'ordered_price.*' => 'nullable|numeric|min:1',
            'set_qty'   => 'required|array',
            'set_qty.*' => 'nullable|integer|min:0|max:100',
            'not_completed_reason' => 'nullable|array',
            'reports' => 'nullable|array',
            'report_text' => 'nullable|string'
        ]);
        FacadesDB::transaction(function () use ($data) {
            foreach ($data['reports'] as $id => $status) {
                $report = ServiceReport::firstOrNew(['id' => $id]);
                if ($report) {
                    $report->update([
                        'is_completed' => ($status === 'completed') ? true : false,
                        'not_completed_reason' => $data['not_completed_reason'][$id] ?? null,
                        'set_qty' => $data['set_qty'][$id] ?? 0,
                    ]);
                }
            }
        });
        $order = Order::with('reports')->findOrFail($id);
        if (isset($data['report_text'])) $order->update(['report_text' => $data['report_text']]);
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
                        'latest_activity' => $data['status']
                    ]);
                abort_if($affected === 0, 404, 'Pivot not found for this stock.');

                $stock = Stock::whereKey($data['stockId'])
                    ->lockForUpdate()
                    ->firstOrFail();
                match ($data['status']) {
                    'increment' => $stock->increment('quantity', $data['qty']),
                    'decrement' => $data['qty'] > $stock->quantity ? abort(422, __('dashboard.insufficient_stock')) : $stock->decrement('quantity', $data['qty']),
                    default => abort(422, __('dashboard.not_found')),
                };
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

    public function generateClientPDF($id)
    {
        $order = Order::with(['customer', 'services'])->findOrFail($id);
        $termsSittng = TermsSittng::firstOrFail();

        //        dd($termsSittng);
        // Generate QR code with link to edit page
        $editUrl = route('orders.edit', $id);

        // Generate QR code using Endroid QR Code library
        try {
            $qrCode = QrCode::create($editUrl)
                ->setSize(100)
                ->setMargin(0)->setBackgroundColor(new Color(255, 255, 255)) // خلفية بيضاء نقية
            ;

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Save QR code to temporary file
            $qrCodePath = 'qrcodes/order_' . $id . '.png';
            Storage::disk('public')->put($qrCodePath, $result->getString());
            $qrCodeFullPath = Storage::disk('public')->path($qrCodePath);
        } catch (\Exception $e) {
            \Log::error('QR code generation failed: ' . $e->getMessage());
            $qrCodeFullPath = null;
        }

        $html = view('dashboard.orders.pdf.reservation', compact('order', 'termsSittng', 'qrCodeFullPath', 'editUrl'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'xbriyaz',
            'directionality' => 'rtl'
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->WriteHTML($html);

        // Clean up QR code file if created
        if (isset($qrCodePath) && Storage::disk('public')->exists($qrCodePath)) {
            Storage::disk('public')->delete($qrCodePath);
        }

        $filename = 'RES-' . $order->order_number . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    public function quote($order)
    {
        $order = Order::with(['payments', 'addons', 'services', 'customer'])->findOrFail($order);
        $termsSittng = TermsSittng::firstOrFail();

        $html = view('dashboard.orders.pdf.quote', compact('order', 'termsSittng'))->render();
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);

        $filename = 'QUO-' . $order->order_number . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    public function invoice($order)
    {
        $order = Order::with(['payments', 'addons', 'services', 'customer'])->findOrFail($order);
        $termsSittng = TermsSittng::firstOrFail();
        //        dd($order->items);

        $html = view('dashboard.orders.pdf.invoice', compact('order', 'termsSittng'))->render();
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);
        $mpdf->WriteHTML($html);

        $filename = 'INV-' . $order->order_number . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    public function addonReceipt($orderId, $addonOrderId)
    {
        $order = Order::with(['payments', 'addons', 'services', 'customer', 'items'])
            ->findOrFail($orderId);

        // Get the specific addon for this receipt
        $addon = $order->addons->where('pivot.id', $addonOrderId)->first();

        if (!$addon) {
            abort(404, 'Addon not found for this order');
        }

        $termsSittng = TermsSittng::firstOrFail();

        $html = view('dashboard.orders.pdf.addon_receipt', compact('order', 'addon', 'termsSittng'))->render();
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);
        $mpdf->WriteHTML($html);

        $filename = 'REC-' . $order->order_number . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    public function paymentReceipt($orderId, $paymentId)
    {
        $order = Order::findOrFail($orderId);
        $payment = Payment::with(['account'])->findOrFail($paymentId);

        // Verify the payment belongs to the order
        if ($payment->order_id != $order->id) {
            abort(404, 'Payment not found for this order');
        }
        //dd($payment);
        $termsSittng = TermsSittng::firstOrFail();

        $html = view('dashboard.orders.pdf.payment_receipt', compact('order', 'payment', 'termsSittng'))->render();
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);
        $mpdf->WriteHTML($html);

        $filename = 'REC-' . $order->order_number . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    public function warehouseReceipt($orderId, $warehouseId)
    {
        $order = Order::findOrFail($orderId);
        $warehouseItem = \App\Models\OrderItem::with(['stock', 'order'])->findOrFail($warehouseId);

        // Verify the warehouse item belongs to the order
        if ($warehouseItem->order_id != $order->id) {
            abort(404, 'Warehouse item not found for this order');
        }

        $termsSittng = TermsSittng::firstOrFail();
        //dd($warehouseItem);
        $html = view('dashboard.orders.pdf.warehouse_receipt', compact('order', 'warehouseItem', 'termsSittng'))->render();
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);
        $mpdf->WriteHTML($html);

        $filename = 'REC-' . $order->order_number . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    // Update the generateTempPdf method in OrderController
    private function generateTempPdf($order, $type)
    {
        $termsSittng = TermsSittng::firstOrFail();
        $view = '';
        $qrCodeFullPath = null;

        switch ($type) {
            case 'show_price':
                $view = 'dashboard.orders.pdf.quote';
                break;
            case 'reservation_data':
                $view = 'dashboard.orders.pdf.reservation';
                // Generate QR code for reservation data like in generateClientPDF
                try {
                    $editUrl = route('orders.edit', $order->id);
                    $qrCode = QrCode::create($editUrl)
                        ->setSize(100)
                        ->setMargin(0)
                        ->setBackgroundColor(new Color(255, 255, 255));

                    $writer = new PngWriter();
                    $result = $writer->write($qrCode);

                    $qrCodePath = 'qrcodes/order_' . $order->id . '.png';
                    Storage::disk('public')->put($qrCodePath, $result->getString());
                    $qrCodeFullPath = Storage::disk('public')->path($qrCodePath);
                } catch (\Exception $e) {
                    \Log::error('QR code generation failed: ' . $e->getMessage());
                    $qrCodeFullPath = null;
                }
                break;
            case 'invoice':
                $view = 'dashboard.orders.pdf.invoice';
                break;
            case 'receipt':
                $view = 'dashboard.orders.pdf.receipt';
                break;
            default:
                return false;
        }

        // Render the view with appropriate variables
        if ($type === 'reservation_data') {
            $html = view($view, compact('order', 'termsSittng', 'qrCodeFullPath', 'editUrl'))->render();
        } else {
            $html = view($view, compact('order', 'termsSittng'))->render();
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'xbriyaz',
            'directionality' => 'rtl'
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->WriteHTML($html);

        $tempPath = storage_path('app/temp/' . $type . '_' . $order->id . '.pdf');
        $mpdf->Output($tempPath, \Mpdf\Output\Destination::FILE);

        // Clean up QR code file if created
        if ($type === 'reservation_data' && isset($qrCodePath) && Storage::disk('public')->exists($qrCodePath)) {
            Storage::disk('public')->delete($qrCodePath);
        }

        return $tempPath;
    }
    // Add this method to send email
    public function sendEmail(Request $request, $id)
    {
        $request->validate([
            'documents' => 'sometimes|array',
            'documents.*' => 'in:show_price,reservation_data,invoice,receipt',
            'receipts' => 'sometimes|array'
        ]);

        $order = Order::with(['customer', 'addons', 'payments', 'items.stock'])->findOrFail($id);
        $documentsToSend = [];

        // Generate main documents
        if ($request->has('documents')) {
            foreach ($request->documents as $document) {
                $tempPath = $this->generateTempPdf($order, $document);
                if ($tempPath) {
                    $documentsToSend[] = $document;
                }
            }
        }

        // Generate receipt documents
        if ($request->has('receipts')) {
            foreach ($request->receipts as $type => $ids) {
                foreach ($ids as $itemId) {
                    switch ($type) {
                        case 'addon':
                            $addon = $order->addons->where('pivot.id', $itemId)->first();
                            if ($addon) {
                                $tempPath = $this->generateAddonReceiptPdf($order->id, $itemId);
                                if ($tempPath) {
                                    $documentsToSend[] = "addon_receipt_{$itemId}";
                                }
                            }
                            break;
                        case 'payment':
                            $payment = $order->payments->find($itemId);
                            if ($payment) {
                                $tempPath = $this->generatePaymentReceiptPdf($order->id, $itemId);
                                if ($tempPath) {
                                    $documentsToSend[] = "payment_receipt_{$itemId}";
                                }
                            }
                            break;
                        case 'warehouse':
                            $item = $order->items->find($itemId);
                            if ($item) {
                                $tempPath = $this->generateWarehouseReceiptPdf($order->id, $itemId);
                                if ($tempPath) {
                                    $documentsToSend[] = "warehouse_receipt_{$itemId}";
                                }
                            }
                            break;
                    }
                }
            }
        }

        if (empty($documentsToSend)) {
            return response()->json(['success' => false, 'message' => 'Please select at least one document to send'], 400);
        }

        try {
            Mail::to($order->customer->email)
                ->send(new OrderDocumentsMail($order, $documentsToSend));

            // Clean up temporary files
            $this->cleanupTempFiles($order->id, $documentsToSend);

            return response()->json(['success' => true, 'message' => 'تم إرسال البريد الإلكتروني بنجاح']);
        } catch (\Exception $e) {
            $this->cleanupTempFiles($order->id, $documentsToSend);
            return response()->json(['success' => false, 'message' => 'فشل في إرسال البريد الإلكتروني: ' . $e->getMessage()], 500);
        }
    }

    private function generateAddonReceiptPdf($orderId, $addonId)
    {
        $order = Order::with(['payments', 'addons', 'services', 'customer', 'items'])
            ->findOrFail($orderId);

        $addon = $order->addons->where('pivot.id', $addonId)->first();
        if (!$addon) abort(404, 'Addon not found');

        $termsSittng = TermsSittng::firstOrFail();
        $html = view('dashboard.orders.pdf.addon_receipt', compact('order', 'addon', 'termsSittng'))->render();

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);

        $tempPath = storage_path('app/temp/addon_receipt_' . $addonId . '.pdf');
        $mpdf->Output($tempPath, \Mpdf\Output\Destination::FILE);

        return $tempPath;
    }

    private function generatePaymentReceiptPdf($orderId, $paymentId)
    {
        $order = Order::findOrFail($orderId);
        $payment = Payment::with(['account'])->findOrFail($paymentId);
        if ($payment->order_id != $order->id) abort(404, 'Payment not found');

        $termsSittng = TermsSittng::firstOrFail();
        $html = view('dashboard.orders.pdf.payment_receipt', compact('order', 'payment', 'termsSittng'))->render();

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);

        $tempPath = storage_path('app/temp/payment_receipt_' . $paymentId . '.pdf');
        $mpdf->Output($tempPath, \Mpdf\Output\Destination::FILE);

        return $tempPath;
    }

    private function generateWarehouseReceiptPdf($orderId, $itemId)
    {
        $order = Order::findOrFail($orderId);
        $warehouseItem = \App\Models\OrderItem::with(['stock'])->findOrFail($itemId);
        if ($warehouseItem->order_id != $order->id) abort(404, 'Item not found');

        $termsSittng = TermsSittng::firstOrFail();
        $html = view('dashboard.orders.pdf.warehouse_receipt', compact('order', 'warehouseItem', 'termsSittng'))->render();

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);

        $tempPath = storage_path('app/temp/warehouse_receipt_' . $itemId . '.pdf');
        $mpdf->Output($tempPath, \Mpdf\Output\Destination::FILE);

        return $tempPath;
    }

    private function cleanupTempFiles($orderId, $documents)
    {
        foreach ($documents as $document) {
            if (str_starts_with($document, 'addon_receipt_')) {
                $addonId = str_replace('addon_receipt_', '', $document);
                $tempPath = storage_path('app/temp/addon_receipt_' . $addonId . '.pdf');
            } elseif (str_starts_with($document, 'payment_receipt_')) {
                $paymentId = str_replace('payment_receipt_', '', $document);
                $tempPath = storage_path('app/temp/payment_receipt_' . $paymentId . '.pdf');
            } elseif (str_starts_with($document, 'warehouse_receipt_')) {
                $itemId = str_replace('warehouse_receipt_', '', $document);
                $tempPath = storage_path('app/temp/warehouse_receipt_' . $itemId . '.pdf');
            } else {
                $tempPath = storage_path('app/temp/' . $document . '_' . $orderId . '.pdf');
            }

            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
    }
}
