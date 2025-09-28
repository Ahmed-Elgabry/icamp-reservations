<?php

namespace App\Http\Controllers\Dashboard;

use App\Mail\OrderDocumentsMail;
use App\Models\Notice;
use App\Models\Payment;
use App\Services\WhatsAppService;
use App\Models\WhatsappMessageTemplate;
use App\Models\ServiceSiteAndCustomerService;
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
use App\Models\InternalNote;
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
use App\Models\GeneralPayment;
use App\Models\OrderItem;
use App\Models\OrderInternalNote;
use App\Repositories\IUserRepository;
use App\Repositories\IOrderRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ICategoryRepository;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Models\Transaction;
use App\Models\StockAdjustment;
use App\Models\OrderAsset;
use App\Services\StockAdjustmentService;
//use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Http\Requests\UpdateOrderSignInRequest;

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
    public function updateInsuranceStatusAndPrice($order, $price, $originalInsuranceAmount, $insuranceStatus, $confiscationDescription, $insuranceAmount)
    {
        switch ($insuranceStatus) {
            case 'returned':
                foreach ($order->verifiedInsurance()->get() as $key => $insurance) {
                    $insurance->update([
                        'insurance_status' => 'returned',
                        'insurance_handled_by' => auth()->id()
                    ]);
                    // Set transaction amount to 0 since insurance is returned
                    $insurance->transaction->update([
                        'amount' => 0,
                        'insurance_handled_by' => auth()->id()
                    ]);
                    
                    BankAccount::find($insurance->account_id)->decrement('balance', $insurance->price);
                }
                $insuranceAmounts = 0; // No insurance remaining after return
                break;

            case 'confiscated_partial':
                $remainingAmount = $originalInsuranceAmount - $insuranceAmount;
                
                foreach ($order->verifiedInsurance()->get() as $key => $insurance) {
                    //reset the tranaction 
                    $insurance->transaction->update(['amount' => $insurance->price]);
                    $insurance->update([
                        'insurance_status' => 'confiscated_partial',
                        'insurance_handled_by' => auth()->id()
                    ]);
                    // Set transaction to the confiscated amount (what system keeps)
                    if ($insurance->transaction) {
                        $confiscatedPortion = ($insurance->price / $originalInsuranceAmount) * $insuranceAmount;
                        $insurance->transaction->update([
                            'amount' => $confiscatedPortion,
                            'insurance_handled_by' => auth()->id()
                        ]);
                    }

                    // Deduct the confiscated portion from bank balance
                    if ($insurance->account_id) {
                        $confiscatedPortion = ($insurance->price / $originalInsuranceAmount) * $insuranceAmount;
                        BankAccount::find($insurance->account_id)->decrement('balance', $confiscatedPortion);
                    }
                }
                $insuranceAmounts = $remainingAmount;
                break;

            case 'confiscated_full':
                foreach ($order->verifiedInsurance()->get() as $key => $insurance) {
                    $insurance->update([
                        'insurance_status' => 'confiscated_full',
                        'insurance_handled_by' => auth()->id()
                    ]);

                    // Set transaction to 0 since fully confiscated
                    $insurance->transaction->update([
                        'amount' => $insurance->price,
                        'insurance_handled_by' => auth()->id()
                    ]);
                    // Deduct full amount from bank balance
                    if ($insurance->account_id) {
                        BankAccount::find($insurance->account_id)->increment('balance', $insurance->price);
                    }
                }
                $insuranceAmounts = 0; // No insurance remaining after full confiscation
                break;

            default:
                $insuranceAmounts = $originalInsuranceAmount;       
        }

        $order->update([
            'insurance_handled_by' => auth()->id(),
            'insurance_status' => $insuranceStatus,
            'confiscation_description' => $confiscationDescription,
            'insurance_amount' => $insuranceAmounts,
        ]);
        \Log::info(json_encode($order));
    }

    public function create()
    {
        $this->authorize('create', Order::class);

        $customers = Customer::select('id', 'name')->get();
        $services = Service::select('id', 'name', 'price')->get();

        $internalNotes = InternalNote::orderBy('created_at', 'desc')->get();

        return view('dashboard.orders.create', [
            'customers' => $customers,
            'services' => $services,
            'internalNotes' => $internalNotes, 
        ]);
    }

    public function updateInsurance(Request $request, $orderId)
    {
        $validatedData = $request->validate([
            'insurance_status' => 'required|in:returned,confiscated_full,confiscated_partial',
            'confiscation_description' => 'nullable|string',
            'partial_confiscation_amount' => 'required_if:insurance_status,confiscated_partial|min:0',
        ]);
        $order = Order::findOrFail($orderId);
        if ($order->verifiedInsurance()->count() == 0) {
            foreach ($order->verifiedInsurance()->get() as $key => $insurance) {
                $insurance->update([
                    'insurance_status' => null,
                ]);
            }
            $order->update([
                'insurance_status' => null,
            ]);
            return redirect()->back()->withErrors(['insurance_amount' => __('dashboard.no_approved_insurance')]);
        }

        if (!$order->insurance_approved) {
            return redirect()->back();
        }

        if ($order->insurance_status == $validatedData["insurance_status"]) {
            return redirect()->back();
        }
        $originalInsuranceAmount = $order->verifiedInsuranceAmount();
        $price = $order->price;
        
        if ($originalInsuranceAmount <= 0) {
            return back()->withErrors(['insurance_amount' => __('dashboard.no_insurance_to_return')]);
        }
        
        $insuranceAmount = 0;
        if ($validatedData['insurance_status'] === 'confiscated_partial') {
            $insuranceAmount = (float)$request->input('partial_confiscation_amount');
            // Ensure the partial amount is not greater than the original amount
            if ($insuranceAmount > $originalInsuranceAmount) {
                return back()->withErrors(['partial_confiscation_amount' => __('dashboard.confiscation_amount_exceeds_original')]);
            }
            if ($insuranceAmount <= 0) {
                return back()->withErrors(['partial_confiscation_amount' => __('dashboard.enter_valid_confiscation_amount')]);
            }
        }
        $this->updateInsuranceStatusAndPrice($order, $price, $originalInsuranceAmount, $validatedData["insurance_status"], $validatedData["confiscation_description"],  $insuranceAmount);
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
            'expired_price_offer' => 'required_if:status,pending_and_show_price',
            'created_by' => 'required|exists:users,id',
            'agree' => 'nullable|in:1,0',
            'internal_note_id' => 'nullable|exists:internal_notes,id',
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

        // Handle time_from and time_to separately to ensure both are processed independently
        if ($request->filled('time_from')) {
            $validatedData['time_from'] = Carbon::parse($request->time_from)->format('H:i:s');
        }
        
        if ($request->filled('time_to')) {
            $timeTo = Carbon::parse($request->time_to)->format('H:i:s');
            
            // If time_from is set and time_to is before time_from, set time_to to 24:00:00
            if (isset($validatedData['time_from']) && $validatedData['time_from'] > $timeTo) {
                $timeTo = '24:00:00';
            }
            
            $validatedData['time_to'] = $timeTo;
        }

        $order = Order::create($validatedData);

        // Create or update the internal note if one was selected
        if (!empty($validatedData['internal_note_id'])) {
            $internalNote = InternalNote::find($validatedData['internal_note_id']);
            if ($internalNote) {
                OrderInternalNote::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'content' => $validatedData['notes'] ?: $internalNote->note_content,
                        'internal_note_id' => $validatedData['internal_note_id']
                    ]
                );
            }
        }
        // Distribute the provided total price evenly across selected services
        $servicesData = [];
        $serviceCount = max(count($request->service_ids ?? []), 1);
        $perServicePrice = (float) $request->price / $serviceCount;
        foreach ($request->service_ids as $serviceId) {
            $servicesData[$serviceId] = ['price' => $perServicePrice];
        }

        $order->services()->attach($servicesData);
        if ($order->inventory_withdrawal == '1') {
            $this->handleInventoryWithdrawal($order);
        }

        return response()->json([
            'success' => true,
            'message' => __('Order created successfully'),
            'redirect' => route('orders.edit', $order->id)
        ]);
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
        $internalNotes = InternalNote::orderBy('created_at', 'desc')->get();

        // Get the order's internal note if it exists
        $orderInternalNote = OrderInternalNote::with('internalNote')
            ->where('order_id', $order->id)
            ->first();

        $selectedInternalNote = $orderInternalNote ? $orderInternalNote->internal_note_id : null;

        // If we have an internal note, use its content, otherwise use the order notes
        $notes = $order->notes;
        if ($orderInternalNote && $orderInternalNote->content) {
            $notes = $orderInternalNote->content;
        } elseif ($orderInternalNote && $orderInternalNote->internalNote) {
            $notes = $orderInternalNote->internalNote->note_content;
        }

        $additionalNotesData = [
            'notes' => $order->additional_notes,
            'show_price' => $order->show_price,
            'order_data' => $order->order_data,
            'invoice' => $order->invoice,
            'receipt' => $order->receipt,
            'internal_note_content' => $notes
        ];

        // Pass the notes to the order object so they'll be displayed in the form
        $order->notes = $notes;

        return view('dashboard.orders.create', [
            'order' => $order,
            'customers' => $customers,
            'services' => $services,
            'addonsPrice' => $addonsPrice,
            'additionalNotesData' => $additionalNotesData,
            'internalNotes' => $internalNotes,
            'selectedInternalNote' => $selectedInternalNote
        ]);
    }

    public function insurance($id)
    {
        $order = Order::findOrFail($id);
        $insurances = Payment::with(['account'])
            ->where('statement', 'the_insurance')
            ->where('order_id', $id)
            ->where('verified', "1")
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.orders.insurance', compact('order', 'insurances'));
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
            'internal_note_id' => 'nullable|exists:internal_notes,id',
            'notes' => 'nullable|string',
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
            // Set total price directly from request instead of recalculating from services
            $order->price = $request->price;
            $service_ids = array_map('intval', $request->service_ids);
            \DB::table('order_service')->where('order_id', $order->id)->delete();

            // Distribute the provided total price evenly across selected services
            $serviceCount = max(count($service_ids), 1);
            $perServicePrice = (float) $request->price / $serviceCount;
            foreach ($service_ids as $serviceId) {
                \DB::table('order_service')->insert([
                    'order_id' => $order->id,
                    'service_id' => $serviceId,
                    'price' => $perServicePrice,
                ]);
            }

            // Handle internal note update
            if (!empty($validatedData['internal_note_id'])) {
                $internalNote = InternalNote::find($validatedData['internal_note_id']);
                if ($internalNote) {
                    OrderInternalNote::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'content' => $validatedData['notes'] ?: $internalNote->note_content,
                            'internal_note_id' => $validatedData['internal_note_id']
                        ]
                    );
                }
            } else {
                // Remove the internal note if none is selected
                OrderInternalNote::where('order_id', $order->id)->delete();
            }

            $order->save();

            // Send WhatsApp reservation data message if status changed to approved
            if ($request->status === 'approved' && $order->wasChanged('status')) {
                $this->sendReservationDataWhatsApp($order);
            }

            \DB::commit();

            return response()->json(['success' => true, 'message' => 'Order updated successfully']);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Order update failed: ' . $e->getMessage());
            return redirect()->route('orders.edit', $order->id)->with('error', 'Error updating order');
        }
    }


    public function destroy(Request $request, $id)
    {
        $order = $this->orderRepository->findOne($id);
        $this->authorize('delete', $order);
        $this->orderRepository->forceDelete($id);

        // Check if redirect_back parameter is present
        if ($request->has('redirect_back')) {
            return redirect($request->redirect_back)->with('success', __('dashboard.deleted_successfully'));
        }

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
            'handled_by' => auth()->id()
        ]);
        // add transaction linked to the newly created pivot row
        // Fetch the latest pivot row for this order/addon pair (assumes 'order_addon' has an auto-increment 'id')
        $pivot = \App\Models\OrderAddon::where('order_id', $order->id)
            ->where('addon_id', $validatedData['addon_id'])
            ->orderByDesc('id')
            ->first();

        if ($pivot) {
            Transaction::create([
                'order_addon_id' => $pivot->id,
                'account_id' => $request->input('account_id'),
                'order_id' => $order->id,
                'amount' => $validatedData['price'],
                'description' => $validatedData['description'],
                'source' => 'reservation_addon',
                'type' => 'deposit',
                'handled_by' => auth()->id()
            ]);
        }
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

        // Get the existing addon data to find the transaction
        $existingAddon = \DB::table('order_addon')->where('id', $pivotId)->first();
        // discount the amount added in creation
        $discountedBalance = $existingAddon->price;
        if ($existingAddon->verified) {
            BankAccount::findOrFail($existingAddon->account_id)->decrement('balance', $discountedBalance);
        }
        // Update the addon in pivot table
        \DB::table('order_addon')->where('id', $pivotId)->update([
            'addon_id' => $validatedData['addon_id'],
            'count' => $validatedData['count'],
            'account_id' => $validatedData['account_id'] ?? null,
            'payment_method' => $validatedData['payment_method'] ?? null,
            'price' => $validatedData['price'],
            'description' => $validatedData['description'] ?? '',
            'verified' => 0, // Reset verified status on update
            'handled_by' => auth()->id()
        ]);
        $transaction = \App\Models\Transaction::where('order_addon_id', $pivotId)->first();
        if ($transaction) {
            // Update the transaction amount
            $transaction->update([
                'amount' => $validatedData['price'],
                'account_id' => $validatedData['account_id'] ?? $transaction->account_id,
                'description' => $validatedData['description'] ?? $transaction->description,
                'verified' => 0, // Reset verified status on update
                'handled_by' => auth()->id()
            ]);
        }

        return back()->with('success', __('dashboard.success'));
    }

    public function removeAddon($pivotId)
    {
        $existingAddon = DB::table('order_addon')->where('id', $pivotId)->first();
        $order = Order::findOrFail($existingAddon->order_id);

        if (!$existingAddon) {
            return back()->with('error', __('dashboard.addon_not_found'));
        }
        if ($existingAddon->verified) {
            BankAccount::findOrFail($existingAddon->account_id)->decrement('balance', $existingAddon->price);
        }
        // Handle transaction deletion if it exists
        if ($existingAddon->transaction_id) {
            $transaction = \App\Models\Transaction::find($existingAddon->transaction_id);
            if ($transaction) {
                $transaction->delete();
            }
        }
        // Calculate the price difference
        $priceDifference = $existingAddon->price;


        // Delete the pivot record
        DB::table('order_addon')->where('id', $pivotId)->delete();

        return back()->with('success', __('dashboard.success'));
    }

    public function addons($id)
    {
        $order = Order::with('addons')->findOrFail($id);
        $addons = Addon::latest()->get();
        $bankAccounts = BankAccount::latest()->get();

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
            'orderId' => 'required|integer|exists:orders,id',
        ]);
        try {
            $service = new StockAdjustmentService();
            $result = $service->decrmentOrIncrementStock($data);
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

    public function boardToday(Request $request)
    {
        // $this->authorize('viewAny', Order::class);
        $today = Carbon::today()->toDateString();
        $todayOrders = Order::with(['customer', 'services', 'payments'])
            ->whereDate('date', $today)
            ->latest()
            ->get();

        return view('dashboard.orders.board', [
            'activeTab' => 'today',
            'todayOrders' => $todayOrders,
            'upcomingOrders' => collect(),
            'selectedFrom' => $today,
            'selectedTo' => null,
            'formAction' => route('pages.reservations.board.today'),
        ]);
    }

    public function boardUpcoming(Request $request)
    {
        // $this->authorize('viewAny', Order::class);
        $today = Carbon::today()->toDateString();
        $dateFrom = $request->query('from', $today);
        $dateTo = $request->query('to');

        $upcomingQuery = Order::with(['customer', 'services', 'payments'])
            ->whereDate('date', '>', $dateFrom);
        if (!empty($dateTo)) {
            $upcomingQuery->whereDate('date', '<=', $dateTo);
        }
        $upcomingOrders = $upcomingQuery
            ->orderBy('date')
            ->orderBy('time_from')
            ->limit(500)
            ->get();

        return view('dashboard.orders.board', [
            'activeTab' => 'upcoming',
            'todayOrders' => collect(),
            'upcomingOrders' => $upcomingOrders,
            'selectedFrom' => $dateFrom,
            'selectedTo' => $dateTo,
            'formAction' => route('pages.reservations.board.upcoming'),
        ]);
    }
    
    /**
     * Update the sign-in information for an order
     *
     * @param  \App\Http\Requests\UpdateOrderSignInRequest  $request
     * @param  int  $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
 

    public function updatesignin(UpdateOrderSignInRequest $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Find or create order asset record
        $orderAsset = OrderAsset::firstOrNew(['order_id' => $order->id]);
        
        // Update notes if provided
        if ($request->filled('notes')) {
            $orderAsset->notes = $request->notes;
        }
        
        // Save the order asset to get an ID before handling file uploads
        $orderAsset->save();
        
        // Handle file uploads and removals
        $this->handleItemAttachments($request, $orderAsset);
        
        // Update order details
        $order->update([
            'time_of_receipt' => $request->time_of_receipt ?: $order->time_of_receipt,
            'time_of_receipt_notes' => $request->time_of_receipt_notes ?: $order->time_of_receipt_notes,
            'delivery_time' => $request->delivery_time ?: $order->delivery_time,
            'delivery_time_notes' => $request->delivery_time_notes ?: $order->delivery_time_notes,
        ]);

        return redirect()->back()->with('success', __('dashboard.success'));
    }
    /**
     * Handle file uploads and deletions for order item attachments
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $orderAsset
     * @param string $type Type of attachment (video/audio/photo)
     * @param string $fileInputName Name of the file input in the request
     * @param string $pathColumn Name of the database column to store the path
     * @param string $storagePath Storage path for the file
     * @return void
     */
    protected function handleFileUpload($request, &$orderAsset, $type, $fileInputName, $pathColumn, $storagePath)
    {
        // Handle file upload
        if ($request->hasFile($fileInputName)) {
            // Delete old file if exists
            if ($orderAsset->$pathColumn) {
                Storage::disk('public')->delete($orderAsset->$pathColumn);
            }
            
            try {
                // Store new file
                $path = $request->file($fileInputName)->store($storagePath, 'public');
                $orderAsset->$pathColumn = $path;
                $orderAsset->save();
            } catch (\Exception $e) {
                \Log::error('File upload failed: ' . $e->getMessage());
                throw $e;
            }
        } 
        // Handle file removal
        elseif ($request->boolean('remove_' . $fileInputName)) {
            if ($orderAsset->$pathColumn) {
                try {
                    Storage::disk('public')->delete($orderAsset->$pathColumn);
                    $orderAsset->$pathColumn = null;
                    $orderAsset->save();
                } catch (\Exception $e) {
                    \Log::error('File deletion failed: ' . $e->getMessage());
                    // Continue even if deletion fails to prevent the whole operation from failing
                }
            }
        }
    }

    /**
     * Handle all order item attachments (video, audio, photo)
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\OrderAsset $orderAsset
     * @return void
     */
    protected function handleItemAttachments($request, $orderAsset)
    {
        // Handle video upload/removal
        $this->handleFileUpload(
            $request,
            $orderAsset,
            'video',
            'video',
            'video_path',
            'camp-reports'
        );

        // Handle audio upload/removal
        $this->handleFileUpload(
            $request,
            $orderAsset,
            'audio',
            'audio',
            'audio_path',
            'orders/audios'
        );

        // Handle photo upload/removal
        $this->handleFileUpload(
            $request,
            $orderAsset,
            'photo',
            'photo',
            'image_path',
            'orders/photos'
        );
    }


    public function uploadTemporaryImage(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'pre_login_image' => 'required|array',
            'pre_login_image.*' => 'mimes:jpeg,png,jpg,gif,svg|max:20480'
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
            if ($type == 'addon') {
                $item = OrderAddon::findOrFail($id);
                $transaction = Transaction::where('order_addon_id', $item->id)->first();
            } elseif ($type == 'payment') {
                \Log::info($id);
                $item = Payment::findOrFail($id);
                $transaction = Transaction::where('payment_id', $item->id)->first();
            } elseif ($type == 'general_revenue_deposit') {
                $item = GeneralPayment::findOrFail($id);
                $transaction = $item->transaction()->first();
                \Log::info($item);
            } elseif ($type == 'stockTaking') {
                $item = StockAdjustment::findOrFail($id);
            } elseif ($type == 'insurance') {
                $item = Order::findOrFail($id);
                event(new \App\Events\VerificationStatusChanged('insurance', $item, $item->insurance_approved));
                return redirect()->back()->with('success', __('dashboard.success'));
            } elseif ($type == 'expense') {
                $item = Expense::findOrFail($id);
                $transaction = $item->transaction()->first();
            } elseif ($type == 'warehouse_sales') {
                $item = OrderItem::findOrFail($id);
                $transaction = Transaction::where('order_item_id', $item->id)->first();
            } else {
                return redirect()->back()->with('error', __('dashboard.invalid_type'));
            }
            $newVerifiedStatus = !$item->verified;

            $item->update(["verified" => $newVerifiedStatus]);
            if (isset($transaction)) {
                $transaction->update(["verified" => $newVerifiedStatus]);
            }
            // Fire event so bank balance is adjusted by listener
            event(new \App\Events\VerificationStatusChanged(match ($type) {
                'addon' => 'addon',
                'payment' => 'payment',
                'expense' => 'expense',
                'general_revenue_deposit' => 'general_revenue_deposit', // Map to 'payment' since it's handled the same way
                'insurance' => 'insurance',
                'stockTaking' => 'stockTaking',
                'warehouse_sales' => 'warehouse_sales',
                default => $type,
            }, $item, $newVerifiedStatus));

            return redirect()->back()->with('success', __('dashboard.success'));
        } catch (\Exception $e) {
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

        // Ensure temp directory exists
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

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

    // Add WhatsApp sending method
    public function sendWhatsApp(Request $request, $id)
    {
        $request->validate([
            'templates' => 'sometimes|array',
            'templates.*' => 'in:show_price,reservation_data,invoice,receipt,evaluation,payment_link_created,payment_link_resend,booking_reminder,booking_ending_reminder,manual_template',
            'receipts' => 'sometimes|array'
        ]);

        $order = Order::with(['customer', 'addons', 'payments', 'items.stock'])->findOrFail($id);
        $whatsAppService = new WhatsAppService();

        if (!$order->customer || !$order->customer->phone) {
            return response()->json(['success' => false, 'message' => 'لا يوجد رقم هاتف للعميل'], 400);
        }

        // Get service site data for placeholders
        $serviceSiteData = ServiceSiteAndCustomerService::getLatestForWhatsApp();

        $messagesCount = 0;
        $errors = [];

        // Process main templates
        if ($request->has('templates')) {
            foreach ($request->templates as $templateType) {
                try {
                    $template = WhatsappMessageTemplate::getByType($templateType);

                    if (!$template) {
                        $errors[] = "Template not found for type: {$templateType}";
                        continue;
                    }

                    // Get bilingual processed message with customer data (Arabic + English)
                    if ($templateType === 'evaluation') {
                        // Send evaluation with survey link (public route that doesn't require auth)
                        $surveyUrl = route('surveys.public', ['order' => $order->id]);
                        $message = $template->getBilingualMessage($order->customer->name, $surveyUrl, $serviceSiteData);

                        // Log the generated URL and message for debugging
                        Log::info('Evaluation message prepared', [
                            'order_id' => $order->id,
                            'survey_url' => $surveyUrl,
                            'customer_name' => $order->customer->name,
                            'message_preview' => substr($message, 0, 100) . '...'
                        ]);

                        // Validate that the message contains the evaluation link
                        if (empty($surveyUrl) || strpos($message, 'undefined') !== false) {
                            $errors[] = "Failed to generate evaluation link for order {$order->id}";
                            continue;
                        }

                        // Use link preview for evaluation messages
                        $success = $whatsAppService->sendLinkPreview(
                            $order->customer->phone,
                            $surveyUrl,
                            $message
                        );
                    } else {
                        // Get bilingual message for non-evaluation templates
                        $message = $template->getBilingualMessage($order->customer->name, '', $serviceSiteData);

                        // Send message with PDF file
                        $pdfPath = $this->generateTempPdf($order, $templateType);
                        if ($pdfPath && file_exists($pdfPath)) {
                            $success = $whatsAppService->sendFile(
                                $order->customer->phone,
                                $pdfPath,
                                $message
                            );
                            // Clean up temp file
                            if (file_exists($pdfPath)) {
                                unlink($pdfPath);
                            }
                        } else {
                            // Send text message if PDF generation fails
                            $success = $whatsAppService->sendTextMessage($order->customer->phone, $message);
                        }
                    }

                    if ($success) {
                        $messagesCount++;
                    } else {
                        $errors[] = "Failed to send {$templateType} message";
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error sending {$templateType}: " . $e->getMessage();
                }
            }
        }

        // Process receipt templates (same logic as email)
        if ($request->has('receipts')) {
            foreach ($request->receipts as $type => $ids) {
                foreach ($ids as $itemId) {
                    try {
                        $template = WhatsappMessageTemplate::getByType('receipt');
                        if (!$template) continue;

                        $message = $template->getBilingualMessage($order->customer->name, '', $serviceSiteData);
                        $pdfPath = null;

                        switch ($type) {
                            case 'addon':
                                $pdfPath = $this->generateAddonReceiptPdf($order->id, $itemId);
                                break;
                            case 'payment':
                                $pdfPath = $this->generatePaymentReceiptPdf($order->id, $itemId);
                                break;
                            case 'warehouse':
                                $pdfPath = $this->generateWarehouseReceiptPdf($order->id, $itemId);
                                break;
                        }

                        if ($pdfPath && file_exists($pdfPath)) {
                            $success = $whatsAppService->sendFile(
                                $order->customer->phone,
                                $pdfPath,
                                $message
                            );
                            // Clean up temp file
                            if (file_exists($pdfPath)) {
                                unlink($pdfPath);
                            }

                            if ($success) {
                                $messagesCount++;
                            } else {
                                $errors[] = "Failed to send {$type} receipt";
                            }
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Error sending {$type} receipt: " . $e->getMessage();
                    }
                }
            }
        }

        if ($messagesCount > 0) {
            $message = $messagesCount === 1
                ? 'تم إرسال رسالة واتساب واحدة بنجاح'
                : "تم إرسال {$messagesCount} رسائل واتساب بنجاح";

            if (!empty($errors)) {
                $message .= ' مع بعض الأخطاء: ' . implode(', ', $errors);
            }

            return response()->json(['success' => true, 'message' => $message]);
        } else {
            $errorMessage = !empty($errors)
                ? 'فشل في إرسال رسائل الواتساب: ' . implode(', ', $errors)
                : 'لم يتم إرسال أي رسائل واتساب';

            return response()->json(['success' => false, 'message' => $errorMessage], 500);
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

        // Ensure temp directory exists
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

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

        // Ensure temp directory exists
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

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

        // Ensure temp directory exists
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

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

    /**
     * Send reservation data WhatsApp message when order is approved
     *
     * @param Order $order
     * @return void
     */
    private function sendReservationDataWhatsApp(Order $order)
    {
        try {
            // Check if customer has phone number
            if (!$order->customer || !$order->customer->phone) {
                Log::warning('Order customer has no phone number for WhatsApp reservation data', [
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id
                ]);
                return;
            }

            // Get the reservation data template
            $template = WhatsappMessageTemplate::getByType('reservation_data');
            if (!$template) {
                Log::warning('No active reservation data template found', ['order_id' => $order->id]);
                return;
            }

            // Get service site data for placeholders
            $serviceSiteData = ServiceSiteAndCustomerService::getLatestForWhatsApp();

            // Get bilingual message
            $message = $template->getBilingualMessage($order->customer->name, '', $serviceSiteData);

            // Generate PDF
            $pdfPath = $this->generateTempPdf($order, 'reservation_data');

            // Send WhatsApp message
            $whatsAppService = new WhatsAppService();

            if ($pdfPath && file_exists($pdfPath)) {
                $success = $whatsAppService->sendFile(
                    $order->customer->phone,
                    $pdfPath,
                    $message
                );

                // Clean up temp file
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
            } else {
                // Send text message if PDF generation fails
                $success = $whatsAppService->sendTextMessage($order->customer->phone, $message);
            }

            if ($success) {
                Log::info('WhatsApp reservation data message sent successfully', [
                    'order_id' => $order->id,
                    'customer_phone' => $order->customer->phone
                ]);
            } else {
                Log::error('Failed to send WhatsApp reservation data message', [
                    'order_id' => $order->id,
                    'customer_phone' => $order->customer->phone
                ]);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp reservation data sending failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
