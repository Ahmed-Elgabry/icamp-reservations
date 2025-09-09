<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\BankAccount;
use App\Models\Expense;
use App\Models\GeneralPayment;
use Illuminate\Http\Request;
use PDF;
use DB;
use Mpdf\Mpdf;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class PaymentsController extends Controller
{


    public function transactions()
    {

        $startDate = request('start_date');
        $endDate = request('end_date');

        $transactions = Transaction::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->where(function ($query) {
                $query->where('verified', "1");
                    //   ->whereHas('payment', fn($q) => $q->whereNot('insurance_status', 'returned'))
                    //   ->orWhere("source", "charge_account")//they will have verificaion button 
                    //   ->orWhere("source", "general_payments_deposit");//they will have verificaion button 
            }) // this is the transaction source for the page which have not approved button
            ->where("amount", ">", 0)
            ->get();
        $transactions = $transactions->sortByDesc('created_at');

        return view('dashboard.banks.transactions', [
            'transactions' => $transactions
        ]);
    }

    public function transactionsDestroy($id)
    {
        $payment = Transaction::findOrFail($id);
        $bankAccount = BankAccount::find($payment->account_id);
        $disccountFormAccount = $payment->amount;
        $amount = $payment->amount;

        // Only refund the amount to the bank account if the transaction was verified
        if ($payment->verified) {
            $bankAccount->update([
            'balance' => $bankAccount->balance + $disccountFormAccount
            ]);
        }

        if ($payment->receiver_id) {
            $transfareTo = BankAccount::find($payment->receiver_id);
            $transfareTo->update([
                'balance' => $transfareTo->balance - $amount
            ]);
        }

        $payment->delete();
        return response()->json();
    }


    public function index(Request $request)
    {
        $this->authorize('viewAny', Payment::class);

        $query = Payment::with(['order.customer', 'order.addons', 'order.items', 'order.expenses'])->where(
            fn($q) =>
            $q->whereNot('statement', 'the_insurance')
        );

        if ($request->customer_id) {
            $query->whereHas('order.customer', function ($q) use ($request) {
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
        $payments = $query->where('verified', "1")->get();

        $orders = Order::whereNot('insurance_status', 'returned')->get();
        $customers = Customer::all();
        return view('dashboard.payments.index', compact('payments', 'customers', 'orders'));
    }

    public function create($bankAccount = null)
    {
        $this->authorize('create', Payment::class);
        $selectedBank = $bankAccount ? BankAccount::findOrFail($bankAccount) : null;
        $bankAccounts = BankAccount::all();
        
        // Fetch recent account charges instead of general payments
        $recentAccountCharges = GeneralPayment::with(['account', 'transaction'])
            ->whereHas('transaction', function ($q) {
                $q->where('source', 'account_charge');
            })
            ->latest()
            ->limit(10)
            ->get();
            
        return view('dashboard.payments.create', [
            'bankAccount' => $selectedBank,
            'bankAccounts' => $bankAccounts,
            'recentAccountCharges' => $recentAccountCharges
        ]);
    }

    public function transfer($bankAccount = null)
    {
        $selectedBank = $bankAccount ? BankAccount::findOrFail($bankAccount) : null;
        $bankAccounts = BankAccount::all();
        return view('dashboard.payments.transfer', [
            'bankAccount' => $selectedBank,
            'bankAccounts' => $bankAccounts
        ]);
    }
    

    public function edit($id)
    {
        $bankAccounts = BankAccount::all();
        $payment = Transaction::findOrFail($id);
        $firstBankAccount = BankAccount::first();

        return view('dashboard.payments.create', [
            'bankAccounts' => $bankAccounts,
            'firstBankAccount' => $firstBankAccount,
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
        'source' => 'nullable|string',
        'description' => 'nullable|string',
        'image' => 'nullable|image',
    ]);
    
    DB::beginTransaction();
    
    try {
        $validatedData = array_merge($validatedData, [
            'type' => "deposit"
                ]);
        // Handle optional photo
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('payments/images', 'public');
            $validatedData['image_path'] = $path;
        }
        $validatedData["price"] = $validatedData["amount"];
        $payment = GeneralPayment::create($validatedData);
        $transaction = $payment->transaction()->create($validatedData);

        if ($request->filled('receiver_id')) {
            $transfareTo = BankAccount::find($request->receiver_id);
            $transfareTo->increment('balance', $request->amount);
        }

        DB::commit();
        return response()->json(['message' => 'Transaction successful'], 200);
        
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Account store transaction failed: ' . $e->getMessage());
        return response()->json(['message' => 'Transaction failed'], 500);
    }
}

public function moneyTransfer(Request $request)
{
    $validatedData = $request->validate([
        'amount' => 'required|numeric|min:0',
        'receiver_id' => 'required|exists:bank_accounts,id',
        'sender_account_id' => 'required|exists:bank_accounts,id',
        "type" => "required|in:deposit,debit,transfer",
        'date' => 'required|date',
        'description' => 'nullable|string',
    ]);
    
    DB::beginTransaction();
    
    try {
        \Log::info(json_encode($validatedData));
        $validatedData = array_merge($validatedData, [
            'verified' => true , 
        ]);
        
        Transaction::create($validatedData);
        $receiverBankAccount = BankAccount::find($request->receiver_id);
        $receiverBankAccount->increment('balance', $request->amount);

        $senderBankAccount = BankAccount::find($request->sender_account_id);
        $senderBankAccount->decrement('balance', $request->amount);

        DB::commit();
        return response()->json(['message' => 'Transaction successful'], 200);
        
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Money transfer transaction failed: ' . $e->getMessage());
        return response()->json(['message' => 'Transaction failed'], 500);
    }
}


    public function accountsUpdate(Request $request, $id)
    {
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
            'payment_method' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        DB::beginTransaction();
        
        try {
            // Handle optional replacement image
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('payments/images', 'public');
                $validatedData['image_path'] = $path;
            }
            
            $payment = GeneralPayment::findOrFail($id);
            if ($payment->verified) {
                $oldAccount = BankAccount::find($account_id);
                if ($oldAccount) {
                    $oldAccount->increment('balance', $disccountFormAccount);
                }
            }
            $validatedData['verified'] = 0;
            $payment->transaction()->update($validatedData);
            $validatedData['price'] = $validatedData['amount']; // Sync price with amount
            $payment->update($validatedData);


            // send money to new receiver account
            if ($request->filled('receiver_id')) {
                $newAccount = BankAccount::find($request->receiver_id);
                if ($newAccount) {
                    $newAccount->increment('balance', $amount);
                }
                
                // If receiver changed, remove money from old receiver
                if ($receiver && $receiver != $request->receiver_id) {
                    $oldReceiver = BankAccount::find($receiver);
                    if ($oldReceiver) {
                        $oldReceiver->decrement('balance', $paymentAmount);
                    }
                }
            }

            DB::commit();
            
            return response()->json(['message' => 'Update successful'], 200);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Account update transaction failed: ' . $e->getMessage());
            return response()->json(['message' => 'Update failed'], 500);
        }
    }


    public function print($payment)
    {
        $payment = Payment::with('order.customer')->findOrFail($payment);
        $html = view('dashboard.payments.pdf', compact('payment'))->render();

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('invoice.pdf', 'I');
    }

    public function verified($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'verified' => $payment->verified ? '0' : '1'
        ]);
        $payment->transaction()->update([
            'verified' => $payment->verified ? '0' : '1'
        ]);

        return back()->withSuccess(__('dashboard.success'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Payment::class);

        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'account_id' => 'required|exists:bank_accounts,id',
            'price' => 'required|numeric',
            'source' => 'required|string',
            'payment_method' => 'required|string',
            'statement' => 'required',
            'notes' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {

            $payment = Payment::create($validatedData);
            
            if ($request->source) {
                if ($request->statement == 'deposit' || $request->statement == 'complete the amount') {
                    $source = 'reservation_payments';
                } else {
                    $source = 'insurances';
                }
            }
            
            Transaction::create([
                'account_id' => $request->account_id,
                'amount' => $request->price,
                'type' => 'deposit',
                'source'=> $source ? $source : $request->source,
                'date' => now(),
                'description' => 'Payment: ' . $request->statement,
                'payment_id' => $payment->id,
                'verified' => 0, // Changed from false to true so it appears in general payments
                'order_id' => $payment->order_id,
                'customer_id' => $payment->customer_id ?? null
            ]);
            
            DB::commit();
             // If it's an AJAX/JSON request, return JSON to satisfy front-end expectations
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('dashboard.payment_updated_successfully')
                ], 200);
            }
            return back()->withSuccess(__('dashboard.success'));
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Payment store transaction failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Payment creation failed']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($order)
    {
        $this->authorize('view', Payment::class);

        $order = Order::findOrFail($order);
        $bankAccounts = BankAccount::all();

        return view('dashboard.payments.show', [
            'order' => $order,
            'bankAccounts' => $bankAccounts,
            'payments' => $order->payments
        ]);
    }


    public function update(Request $request, $payment)
    {
        $payment = Payment::findOrFail($payment);
        $this->authorize('update', $payment);
        $validatedData = $request->validate([
            'price' => 'required|numeric',
            'account_id' => 'required|exists:bank_accounts,id',
            'payment_method' => 'required|string',
            'statement' => 'required',
            'notes' => 'nullable|string',
        ]);
        $validatedData['verified'] = 0;
        if ($payment->verified) {
            $bankAccount = BankAccount::find($payment->account_id);
            if ($bankAccount) {
                $bankAccount->decrement('balance', $payment->price);
            }
        }
        $payment->update($validatedData);
        Transaction::where('payment_id', $payment->id)->update([
            'account_id' => $request->account_id,
            'amount' => $request->price,
            'type' => 'deposit',
            'source'=> $request->source,
            'date' => now(),
            'description' => 'Payment: ' . $request->statement,
            'verified' => 0, 
        ]);

        return back()->withSuccess(__('dashboard.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy($payment)
    {
        $payment = Payment::findOrFail($payment);
        $this->authorize('delete', $payment);

        DB::beginTransaction();
        
        try {
            // If payment is verified, discount from the bank account
            if ($payment->verified) {
                $bankAccount = BankAccount::find($payment->account_id);
                if ($bankAccount) {
                    $bankAccount->decrement('balance', $payment->price);
                }
            }
            $payment->delete();
            DB::commit();
            return response()->json(["success" => true,"deleted_amount" => $payment->price]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Payment destroy transaction failed: ' . $e->getMessage());
            return response()->json(["success" => false, "message" => "Delete failed"], 500);
        }
    }


    public function deleteAll(Request $request)
    { 
        DB::beginTransaction();
        
        try {
            $this->authorize('delete', Payment::class);
    
            $requestIds = json_decode($request->data);
    
            foreach ($requestIds as $id) {
                $payment = Payment::findOrFail($id);
                    // If payment is verified, discount from the bank account
                    if ($payment->verified) {
                        $bankAccount = BankAccount::find($payment->account_id);
                        if ($bankAccount) {
                            $bankAccount->decrement('balance', $payment->price);
                        }
                    }
                    $payment->delete();
            }
            
            DB::commit();
            return response()->json('success');
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Payment delete all transaction failed: ' . $e->getMessage());
            return response()->json('failed', 500);
        }
    }
}