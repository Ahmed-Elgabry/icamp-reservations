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
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class GeneralPaymentsController extends Controller
{


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

        $bankAccount->update([
            'balance' => $bankAccount->balance + $disccountFormAccount
        ]);

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
        // $paymentsByCustomer = $general_payments->groupBy(function($t){
            //     return optional(optional($t->order)->customer)->id;
            // });

        // Process order payment summaries (iterate by orders instead of customers)
        $orderSummaries = collect();
        foreach ($paymentsByOrder as $orderId => $orderTransactions) {
            $order = $orderTransactions->first()?->order;
            
            // Skip if order is null (orphaned transactions)
            if (!$order || !$order->customer) continue;
            
            // Group transactions by source for this specific order
            $insurances = $orderTransactions->where('source', 'insurances');
            $addonTransactions = $orderTransactions->where('source', 'reservation_addon');
            $warehouseTransactions = $orderTransactions->where('source', 'warehouse_sale');
            \Log::info(json_encode($insurances));
            
            // Calculate totals from transactions (using 'amount' field)
            $insurancesTotal = $insurances->sum('amount');
            $addonsTotal = $addonTransactions->sum('amount');
            $warehouseTotal = $warehouseTransactions->sum('amount');
            // Count items for this order
            $insurancesCount = $insurances->count();
            $addonsCount = $addonTransactions->count();
            $warehouseCount = $warehouseTransactions->count();

            $grandTotal = $insurancesTotal + $addonsTotal + $warehouseTotal;

            // Get latest transaction date for this order
            $latestDate = $orderTransactions->max('created_at');

            $orderSummaries->push((object)[
                'order' => $order,
                'customer' => $order->customer,
                'insurance_total' => $insurancesTotal,
                'insurance_count' => $insurancesCount,
                'addons_total' => $addonsTotal,
                'addons_count' => $addonsCount,
                'warehouse_total' => $warehouseTotal,
                'warehouse_count' => $warehouseCount,
                'grand_total' => $grandTotal,
                'latest_date' => $latestDate
            ]);
        }

        $orders = Order::whereNot('insurance_status' , 'returned')->get();
        // Show all customers in filter, not only those with payments
        $customers = Customer::orderBy('name')->get(['id','name']);

        return view('dashboard.general_payments.index', compact('general_payments', 'customers','orders' , 'paymentsByOrder', 'orderSummaries'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            $bankAccount = BankAccount::find($request->account_id);

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

    public function accountsUpdate(Request $request, $id)
    {
        $payment = Transaction::findOrFail($id);
        $account_id = $payment->account_id;
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        
        try {
            $validatedData['type'] = "deposit";
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($payment->image_path) {
                    \Storage::disk('public')->delete($payment->image_path);
                }
                $imagePath = $request->file('image')->store('general_payments', 'public');
                $validatedData['image_path'] = $imagePath;
            }

            $payment->update($validatedData);
            

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
            'order_id' => 'required|exists:orders,id',
            'account_id' => 'required|exists:bank_accounts,id',
            'price' => 'required|numeric',
            'payment_method' => 'required|string',
            'statement' => 'nullable',
            'notes' => 'nullable|string',
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif"
        ]);


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('general_payments', 'public');
            $validatedData['image_path'] = $imagePath;
        }
        $payment = GeneralPayment::create($validatedData);
        $payment->transaction()->create([
            'amount' => $request->price,
            'source' => $request->source,
            'account_id' => $request->account_id,
            'type' => 'deposit',
            'description' => $request->notes,
            'order_id' => $request->order_id,
        ]);


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


    public function update(Request $request,  $payment)
    {
        $payment = GeneralPayment::findOrFail($payment);
        $validatedData = $request->validate([
            'price' => 'required|numeric',
            'account_id' => 'required|exists:bank_accounts,id',
            'payment_method' => 'required|string',
            'statement' => 'required',
            'notes' => 'nullable|string',
            'order_id' => 'required|exists:orders,id',
        ]);

        $oldBankAccount = BankAccount::find($payment->account_id);
        //  return money back
        $oldBankAccount->update([
            'balance' => $oldBankAccount->balance - $payment->price
        ]);

        $payment->update($validatedData);

        return back()->withSuccess(__('dashboard.success'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy( $payment)
    {
        $payment = GeneralPayment::findOrFail($payment);

        $bankAccount = BankAccount::find($payment->account_id);
        $bankAccount->update([
            'balance' => $bankAccount->balance - $payment->price
        ]);

        $payment->delete();
        return response()->json();
    }


    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if (GeneralPayment::whereIn('id', $ids)->delete()) {
          return response()->json('success');
        } else {
          return response()->json('failed');
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
            ->limit(10)
            ->get();
        
        \Log::info('Recent add funds payments count: ' . $recentPayments->count());
        
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            ]);

            \Log::info('General payment created with ID: ' . $payment->id);

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
            ]);
            
            DB::commit();
            
            // Check if this is an AJAX request
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
}
