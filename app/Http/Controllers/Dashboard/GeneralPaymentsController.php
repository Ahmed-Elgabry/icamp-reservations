<?php

namespace App\Http\Controllers\Dashboard;

use DB;
use PDF;
use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\BankAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\GeneralPayment;
use App\Services\GeneralPaymentSummaryService;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class GeneralPaymentsController extends Controller
{
    protected $generalPaymentSummaryService;

    public function __construct(GeneralPaymentSummaryService $generalPaymentSummaryService)
    {
        $this->generalPaymentSummaryService = $generalPaymentSummaryService;
    }


    public function transactions(){

        $startDate = request('start_date');
        $endDate = request('end_date');

        $transactions = Transaction::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $expenses = Expense::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $general_payments = Payment::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Convert transactions and expenses to a unified collection
        $merged = collect();

        foreach ($transactions as $transaction) {
            $merged->push((object)[
                'account' => $transaction->account,
                'receiver' => $transaction->receiver,
                'order_id' => $transaction->order_id,
                'id' => $transaction->id,
                'date' => $transaction->date,
                'editRoute' => route('general_payments.edit', $transaction->id),
                'destroyRoute' => route('transactions.destroy', $transaction->id),
                'type' => 'Bank-account',
                'amount' => $transaction->amount,
                'description' => $transaction->description,
                'created_at' => $transaction->created_at,
            ]);
        }

        foreach ($expenses as $expense) {
            $merged->push((object)[
                'account' => $expense->account,
                'receiver' => null,
                'order_id' => null,
                'editRoute' => route('expenses.edit', $expense->id),
                'destroyRoute' => route('expenses.destroy', $expense->id),
                'id' => $expense->id,
                'date' => $expense->date,
                'type' => 'Expense',
                'amount' => $expense->price,
                'description' => $expense->notes,
                'created_at' => $expense->created_at,
            ]);
        }

        foreach ($general_payments as $payment) {
            $merged->push((object)[
                'account' => $payment->account,
                'receiver' => null,
                'order_id' => $payment->order_id,
                'editRoute' => route('general_payments.show', $payment->order_id),
                'destroyRoute' => route('general_payments.destroy', $payment->id),
                'id' => $payment->id,
                'date' => $payment->created_at,
                'type' => 'Payment',
                'amount' => $payment->price,
                'description' => $payment->notes,
                'created_at' => $payment->created_at,
            ]);
        }


        // Sort the merged collection by created_at
        $merged = $merged->sortByDesc('created_at');

        // Manually paginate the merged collection
        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10; // Set your desired items per page
        $currentResults = $merged->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedResults = new LengthAwarePaginator($currentResults, $merged->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        // Append query parameters to pagination links
        $paginatedResults->appends(request()->all());


        return view('dashboard.banks.transactions',[
            'transactions' => $paginatedResults
        ]);
    }

    public function transactionsDestroy ($id)
    {
        $payment = Transaction::findOrFail($id);
        $bankAccount = BankAccount::find($payment->account_id);
        $disccountFormAccount = $payment->amount;
        $amount = $payment->amount;

        // Only update bank account balance if transaction is verified
        if ($payment->verified) {
            $bankAccount->update([
            'balance' => $bankAccount->balance - $disccountFormAccount
            ]);
        }

        if($payment->receiver_id)
        {
            $transfareTo =  BankAccount::find($payment->receiver_id);
            $transfareTo->update([
                'balance' => $transfareTo->balance - $amount
            ]);
        }

        $payment->delete();
        return response()->json();
    }


    public function index(Request $request)
    {
        // Use the service to get payment summaries and related data
        $data = $this->generalPaymentSummaryService->getPaymentSummariesWithStats($request);
        
        // Get the original transactions for the view (if needed)
        $query = Transaction::with([
                'order.customer',
                'order.expenses' => fn ($q) => $q->where('verified' , true),
                'order.addons'  => fn ($q) => $q->where('verified', true),
                'order.items'  => fn ($q) => $q->where('verified', true)
            ])
            ->where(fn ($q) =>
                $q->where('source', "insurances")
                ->whereHas("payment", fn ($q) => $q->whereNot('insurance_status', 'returned'))
                ->whereHas("order", fn ($q) => $q->where('insurance_status', "1"))
                ->orWhere('source', 'reservation_addon')
                ->orWhere('source', 'warehouse_sale')
            );

        if ($request->customer_id) {
            $query->whereHas('order.customer', function($q) use ($request) {
                $q->where('id', $request->customer_id);
            });
        }

        if ($request->order_id) {
            $query->where('order_id', $request->order_id);
        }

        if ($request->date_from && $request->date_to) {
            $dateFrom = Carbon::parse($request->date_from)->startOfDay();
            $dateTo = Carbon::parse($request->date_to)->endOfDay();
            $query->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        $general_payments = $query->where('verified', "1")->get();
        $paymentsByOrder = $general_payments->groupBy('order_id');

        return view('dashboard.general_payments.index', [
            'general_payments' => $general_payments,
            'customers' => $data['customers'],
            'orders' => $data['orders'],
            'paymentsByOrder' => $paymentsByOrder,
            'orderSummaries' => $data['order_summaries'],
            'statistics' => $data['statistics'] ?? null,
        ]);
    }

    public function create ()
    {
        $orders = Order::all();
        $bankAccounts = BankAccount::all();
        $recentGeneralPayments = GeneralPayment::with('account', 'order.customer')
            ->whereHas('transaction' , fn($q) => $q->where('source' , 'general_revenue_deposit'))
            ->latest()
            ->take(10)
            ->get();
            
        return view('dashboard.general_payments.create',[
            'bankAccounts' => $bankAccounts,
            'orders' => $orders,
            'recentGeneralPayments' => $recentGeneralPayments
        ]);
    }

    public function edit ($id)
    {
        $bankAccounts = BankAccount::all();
        $payment = Transaction::findOrFail($id);

        return view('dashboard.general_payments.create',[
            'bankAccounts' => $bankAccounts,
            'payment' => $payment
        ]);
    }

    public function accountsStore(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'receiver_id' => 'nullable|exists:bank_accounts,id',
            'account_id' => 'required|exists:bank_accounts,id',
            'date' => 'nullable|date',
            'description' => 'nullable|string',
            'source' => 'required|string',
            'order_id' => 'required|exists:orders,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        DB::beginTransaction();
        
        try {
                $validatedData['type'] = "deposit";
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('general_payments', 'public');
                $validatedData['image_path'] = $imagePath;
            }

            $payment = Transaction::create($validatedData);

            if ($request->receiver_id) {
                $transfareTo = BankAccount::find($request->receiver_id);
                $transfareTo->update([
                    'balance' => $transfareTo->balance + $amount
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Payment created successfully']);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('General payment store failed: ' . $e->getMessage());
            return response()->json(['message' => 'Payment creation failed'], 500);
        }
    }

    // New: store a bank account charge (deposit) using GeneralPaymentsController
    public function storeAccountCharge(Request $request)
    {
        $validatedData = $request->validate([
            'price' => 'required|numeric|min:0',
            'account_id' => 'required|exists:bank_accounts,id',
            'date' => 'nullable|date',
            'time' => 'nullable|string',
            'description' => 'nullable|string',
            "date" => 'nullable|date',
            'source' => 'nullable|string',
            'order_id' => 'nullable|exists:orders,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        DB::beginTransaction();
        try {
            // If a time is supplied, merge it into the date field as datetime
            if (!empty($validatedData['date']) && $request->filled('time')) {
                $validatedData['date'] = Carbon::parse($validatedData['date'] . ' ' . $request->input('time'))->toDateTimeString();
            }
            $validatedData['type'] = 'deposit';
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('general_payments', 'public');
                $validatedData['image_path'] = $imagePath;
            }
            $validatedData["amount"] = $validatedData["price"] ;
            $payment = GeneralPayment::create($validatedData);
            $transaction = $payment->transaction()->create($validatedData);

            DB::commit();
            if ($request->wantsJson()) {
                return response()->json(['success' => true ,'message' => __('dashboard.success')]);
            }
            return redirect()->back()->with('success', __('dashboard.success'));
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('storeAccountCharge failed: '.$e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['message' => __('dashboard.error_occurred')], 500);
            }
            return redirect()->back()->with('error', __('dashboard.error_occurred'));
        }
    }

    // New: update a bank account charge using GeneralPaymentsController
    public function updateAccountCharge(Request $request, $id)
    {
        
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'account_id' => 'required|exists:bank_accounts,id',
            'date' => 'nullable|date',
            'time' => 'nullable|string',
            'description' => 'nullable|string',
            'order_id' => 'nullable|exists:orders,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        DB::beginTransaction();
        try {
            $payment = GeneralPayment::findOrFail($id);
            $transaction = $payment->transaction();
            $updateData = $validated;
            $updateData['verified'] = 0;
            $updateData['notes'] = $validated["description"];

            // Merge provided time into date if present
            if (!empty($updateData['date']) && $request->filled('time')) {
                $updateData['date'] = Carbon::parse($updateData['date'] . ' ' . $request->input('time'))->toDateTimeString();
            }

            if ($request->hasFile('image')) {
                if ($payment->image_path) {
                    \Storage::disk('public')->delete($payment->image_path);
                }
                $updateData['image_path'] = $request->file('image')->store('general_payments', 'public');
            }

            // Adjust original account balance if previously verified (reverse it)
            if ($payment->verified && $payment ->account_id) {
                BankAccount::where('id', $payment->account_id)
                    ->decrement('balance', (float)$payment->price);
            }
            $payment->update($updateData);
            $updateData["amount"] = $updateData["price"] ;
            unset($updateData["price"], $updateData["image"] , $updateData["notes"] , $updateData["time"]);
            $transaction->update($updateData);

            DB::commit();                                                                                                                                                                                                                                                                                                                                                               
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => __('dashboard.success')]);
            }
            return redirect()->back()->with('success', __('dashboard.success'));
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('updateAccountCharge failed: '.$e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', __('dashboard.error_occurred'));
        }
    }

    public function accountsUpdate(Request $request, $id)
    {
        $payment = GeneralPayment::findOrFail($id);
        $account_id = $payment->account_id;
        $bankAccount = BankAccount::find($account_id);
        $receiver = $payment->receiver_id;
        $disccountFormAccount = $payment->amount;
        $amount = $request->amount;
        $paymentAmount = $payment->amount;

        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'receiver_id' => 'required|exists:bank_accounts,id',
            'account_id' => 'required|exists:bank_accounts,id',
            'date' => 'nullable|date',
            'description' => 'nullable|string',
            'order_id' => 'required|exists:orders,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        DB::beginTransaction();
        
        try {
            $validatedData['type'] = "deposit";
            $validatedData['verified'] = 0;

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($payment->image_path) {
                    \Storage::disk('public')->delete($payment->image_path);
                }
                $imagePath = $request->file('image')->store('general_payments', 'public');
                $validatedData['image_path'] = $imagePath;
            }
            if ($payment->verified) {
                $bankAccount->update([
                    'balance' => $bankAccount->balance - $paymentAmount
                ]);
            }
            $payment->update($validatedData);
            $payment->transaction()->update($validatedData);
            

            // send money to new receiver
            if ($request->receiver_id) {
                $transfareTo = BankAccount::find($request->receiver_id);
                $transfareTo->update([
                    'balance' => $transfareTo->balance + $amount
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Payment updated successfully']);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('General payment update failed: ' . $e->getMessage());
            return response()->json(['message' => 'Payment update failed'], 500);
        }
    }


    public function print($payment)
    {
        $payment = GeneralPayment::with('order.customer')->findOrFail($payment);
        $html = view('dashboard.general_payments.pdf', compact('payment'))->render();

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('invoice.pdf', 'I');

    }

    public function verified ($id)
    {
        $payment = GeneralPayment::findOrFail($id);

        $payment->update([
            'verified' => $payment->verified ? '0' : '1'
        ]);

        return back()->withSuccess(__('dashboard.success'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'account_id' => 'required|exists:bank_accounts,id',
            'price' => 'required|numeric',
            'payment_method' => 'nullable|string',
            'statement' => 'nullable',
            'notes' => 'nullable|string',
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif"
        ]);


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('general_payments', 'public');
            $validatedData['image_path'] = $imagePath;
        }
        // Add handled_by to the validated data
        $validatedData['handled_by'] = auth()->id();
        $payment = GeneralPayment::create($validatedData);
        
        $payment->transaction()->create([
            'amount' => $request->price,
            'source' => $request->source,
            'account_id' => $request->account_id,
            'type' => 'deposit',
            'description' => $request->notes,
            'order_id' => $request->order_id,
            'handled_by' => auth()->id(),

        ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('dashboard.success')
                ], 200);
            }
        return back()->withSuccess(__('dashboard.success'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show( $order)
    {
        $order = Order::with('payments')->findOrFail($order);
        $bankAccounts = BankAccount::all();

        return view('dashboard.general_payments.show',[
            'order' => $order,
            'bankAccounts' => $bankAccounts,
            'general_payments' => $order->general_payments,
            'payments' => $order->payments,
        ]);
    }
    public function getAddFund($id){
        $addFund = GeneralPayment::find($id);
        return response()->json($addFund);
    }

    public function update(Request $request,  $payment)
    {
        $payment = GeneralPayment::findOrFail($payment);
        $validatedData = $request->validate([
            'price' => 'required|numeric',
            'account_id' => 'required|exists:bank_accounts,id',
            'payment_method' => 'nullable|string',
            'statement' => 'nullable',
            'notes' => 'nullable|string',
            'order_id' => 'nullable|exists:orders,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        // If a new image is uploaded, replace the old one
        if ($request->hasFile('image')) {
            if ($payment->image_path) {
                \Storage::disk('public')->delete($payment->image_path);
            }
            $imagePath = $request->file('image')->store('general_payments', 'public');
            $validatedData['image_path'] = $imagePath;
        }

        if ($payment->verified) {
            $bankAccount = BankAccount::find($payment->account_id);
            $bankAccount->update([
                'balance' => $bankAccount->balance - $payment->price
            ]);
        }
        $validatedData['verified'] = 0;
        $validatedData['handled_by'] = auth()->id(); // Add handled_by for the payment update
        $payment->update($validatedData);
        
        // Prepare transaction data
        $transactionData = [
            'account_id' => $validatedData['account_id'],
            'amount' => $validatedData['price'],
            'date' => $payment->date,
            'source' => $payment->source,
            'description' => $validatedData['notes'] ?? null,
            'type' => 'deposit',
            'handled_by' => auth()->id() // Add handled_by for the transaction
        ];
        
        $payment->transaction()->update($transactionData);
        if ($request->ajax() || $request->wantsJson()) { 
            return response()->json([
            'success' => true,
            'message' => __('dashboard.success')
            ], 200);
        }
        return back()->withSuccess(__('dashboard.success'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy($payment, Request $request)
    {
        $payment = GeneralPayment::findOrFail($payment);
        if ($payment->verified) {
            $bankAccount = BankAccount::find($payment->account_id);
            if ($bankAccount) {
            $bankAccount->update([
                'balance' => $bankAccount->balance - $payment->price
            ]);
        }
        }
        // Delete image if it exists
        if ($payment->image_path) {
            \Storage::disk('public')->delete($payment->image_path);
        }
        $payment->delete();
        $payment->transaction()->delete();
        return redirect()->back()->with('success',true);
    }


    public function deleteAll(Request $request) {
        try {
            $requestIds = json_decode($request->data);
            foreach ($requestIds as $id) {
                $payment = GeneralPayment::find($id);
                if (!$payment) {
                    \Log::warning("GeneralPayment not found for ID: $id");
                    continue;
                }
                if ($payment->verified) {
                    $bankAccount = BankAccount::find($payment->account_id);
                    if ($bankAccount) {
                        $bankAccount->update([
                            'balance' => $bankAccount->balance - $payment->price
                        ]);
                    } else {
                        \Log::warning("BankAccount not found for Payment ID: $id, Account ID: {$payment->account_id}");
                    }
                }
                $payment->delete();
            }
            return response()->json(['status' => 'success']);
        } catch (\Throwable $th) {
            \Log::error('Bulk delete failed: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json(['status' => 'failed', 'error' => $th->getMessage()], 500);
        }
    }

    public function downloadImage($id)
    {
        $payment = GeneralPayment::findOrFail($id);
        
        if (!$payment->image_path) {
            abort(404, 'Image not found');
        }

        // Check if file exists in storage
        if (!\Storage::disk('public')->exists($payment->image_path)) {
            abort(404, 'File not found');
        }

        return \Storage::disk('public')->download($payment->image_path, 'general_payment_' . $payment->id . '_image.jpg');
    }

    /**
     * Show the form for creating a new add funds payment.
     */
    public function createAddFunds()
    {
        // Get all bank accounts for the dropdown
        $bankAccounts = BankAccount::all();

        // Get all approved orders for the dropdown (optional)
        $orders = Order::with(['customer'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get recent add funds payments for the table (last 10)
        // Filter by source 'add_funds_page' in the transaction relationship
        $recentPayments = GeneralPayment::with(['account', 'order.customer', 'transaction'])
            ->whereHas('transaction', function($query) {
                $query->where('source', 'add_funds_page');
            })
            ->orWhere('statement', 'add_funds') // Also include by statement type
            ->orderBy('created_at', 'desc')
            ->get();
                
        return view('dashboard.general_payments.create_add_funds', compact(
            'bankAccounts', 
            'orders', 
            'recentPayments'
        ));
    }

    /**
     * Store a newly created add funds payment in storage.
     */
    public function storeAddFunds(Request $request)
    {
       

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'account_id' => 'required|exists:bank_accounts,id',
            'order_id' => 'nullable|exists:orders,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'payment_method' => 'nullable|string',
            'source' => 'required|string',
        ]);

        DB::beginTransaction();
        
        try {
            // Handle image upload if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('general_payments', 'public');
            } else {
                \Log::info('No image file found in add funds request');
            }

            // Create the general payment record
            $payment = GeneralPayment::create([
                'price' => $request->amount,
                'account_id' => $request->account_id,
                'order_id' => $request->order_id,
                'notes' => $request->description,
                'image_path' => $imagePath,
                'statement' => 'add_funds', // Set statement type
                'payment_method' => $request->payment_method,
                'date' => $request->date,
                'verified' => false, // Default to unverified
                'handled_by' => auth()->id()
            ]);

            // Create corresponding transaction
            $transaction = Transaction::create([
                'account_id' => $request->account_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'order_id' => $request->order_id,
                'source' => $request->source ?? 'add_funds_page',
                'description' => $request->description ?? 'Add funds to account',
                'general_payment_id' => $payment->id,
                'type' => 'deposit', // Funds added to account
                'handled_by' => auth()->id()
                
            ]);
            
            DB::commit();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('dashboard.add_funds_success'),
                    'payment_id' => $payment->id
                ]);
            }
            
            return redirect()->route('general_payments.create_add_funds')
                ->with('success', __('dashboard.add_funds_success'));

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Add funds creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            // Check if this is an AJAX request
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('dashboard.error_occurred') . ': ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('dashboard.error_occurred') . ': ' . $e->getMessage());
        }
    }

    /**
     * Update an existing add funds payment.
     */
    public function updateAddFunds(Request $request, $id)
    {
        $payment = GeneralPayment::findOrFail($id);
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'nullable|date',
            'account_id' => 'required|exists:bank_accounts,id',
            'description' => 'nullable|string|max:1000',
            'payment_method' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        DB::beginTransaction();
        
        try {
            // Handle image upload if provided
            $updateData = [
                'price' => $request->amount,
                'account_id' => $request->account_id,
                'notes' => $request->description,
                'payment_method' => $request->payment_method,
                'date' => $request->date,
                'verified' => false, // Reset verification on update
            ];

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($payment->image_path) {
                    \Storage::disk('public')->delete($payment->image_path);
                }
                $imagePath = $request->file('image')->store('general_payments', 'public');
                $updateData['image_path'] = $imagePath;
            }
            if ($payment->verified) {
                BankAccount::where('id', $payment->account_id)->decrement('balance', $payment->price);
            }
            // Update the general payment record
            $payment->update($updateData);

            // Update corresponding transaction if it exists
            if ($payment->transaction) {
                $payment->transaction->update([
                    'account_id' => $request->account_id,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'description' => $request->description ?? 'Updated add funds to account',
                    'verified' => false, // Reset verification on update
                ]);
            }
            
            DB::commit();
            
            // Check if this is an AJAX request
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('dashboard.payment_updated_successfully'),
                    'payment_id' => $payment->id
                ]);
            }
            
            return redirect()->route('general_payments.create_add_funds')
                ->with('success', __('dashboard.payment_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Add funds update failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            // Check if this is an AJAX request
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('dashboard.error_occurred') . ': ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('dashboard.error_occurred') . ': ' . $e->getMessage());
        }
    }
}
