<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\BankAccount;
use App\Models\Expense;
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
            ->get();
        $transactions = Transaction::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        })->whereNotNull('account_id') // تأكد من وجود account_id إذا كان ذلك مطلوبًا

            ->whereHas('account', function ($query) {
                // يتحقق من وجود معرف الحساب
                $query->whereNotNull('id');
            })
            ->whereNotNull('order_id') // يتحقق من وجود order_id
            ->whereNotNull('account_id') // يتحقق من وجود account_id 
            ->orderBy('created_at', 'desc')
            ->get();


        $expenses = Expense::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('date', [$startDate, $endDate]);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        $payments = Payment::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        // Convert transactions and expenses to a unified collection
        $merged = collect();

        foreach ($transactions as $transaction) {
            $merged->push((object) [
                'account' => $transaction->account,
                'receiver' => $transaction->receiver,
                'order_id' => $transaction->order_id,
                'id' => $transaction->id,
                'date' => $transaction->date,
                'editRoute' => route('payments.edit', $transaction->id),
                'destroyRoute' => route('transactions.destroy', $transaction->id),
                'type' => 'Bank-account',
                'amount' => $transaction->amount,
                'description' => $transaction->description,
                'created_at' => $transaction->created_at,
            ]);
        }

        foreach ($expenses as $expense) {
            $merged->push((object) [
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

        foreach ($payments as $payment) {
            $merged->push((object) [
                'account' => $payment->account,
                'receiver' => null,
                'order_id' => $payment->order_id,
                'editRoute' => route('payments.show', $payment->order_id),
                'destroyRoute' => route('payments.destroy', $payment->id),
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


        return view('dashboard.banks.transactions', [
            'transactions' => $paginatedResults
        ]);
    }

    public function transactionsDestroy($id)
    {
        $payment = Transaction::findOrFail($id);
        $bankAccount = BankAccount::find($payment->account_id);
        $disccountFormAccount = $payment->amount;
        $amount = $payment->amount;

        $bankAccount->update([
            'balance' => $bankAccount->balance + $disccountFormAccount
        ]);

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
        $query = Payment::with('order.customer')->where('verified', '1');

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

        $payments = $query->get();

        $orders = Order::all();
        $customers = Customer::all(); // Assuming you have a Customer model
        return view('dashboard.payments.index', compact('payments', 'customers', 'orders'));
    }

    public function create()
    {
        $bankAccounts = BankAccount::all();
        return view('dashboard.payments.create', [
            'bankAccounts' => $bankAccounts
        ]);
    }

    public function edit($id)
    {
        $bankAccounts = BankAccount::all();
        $payment = Transaction::findOrFail($id);

        return view('dashboard.payments.create', [
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
    ]);

    $payment = Transaction::create($validatedData);

    $bankAccount = BankAccount::find($request->account_id);
    $disccountFormAccount = $request->amount;
    $bankAccount->update([
        'balance' => $bankAccount->balance + $disccountFormAccount
    ]);

    if ($request->filled('receiver_id')) {
        $transfareTo = BankAccount::find($request->receiver_id);
        $transfareTo->update([
            'balance' => $transfareTo->balance + $request->amount
        ]);
    }
    
    return response()->json(['message' => 'Transaction successful'], 200);
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
        ]);


        $payment->update($validatedData);
        $oldBankAccount = BankAccount::find($account_id);

        // return money to the accounts  
        // back money to the sender account_id 
        $newBalance = $oldBankAccount->balance + $disccountFormAccount;
        BankAccount::where('id', $account_id)->update(['balance' => $newBalance]);
        // take money form resever account 
        // back money to resever 
        $oldResever = BankAccount::find($receiver);
        $newBalance = $oldResever->balance - $paymentAmount;
        BankAccount::where('id', $receiver)->update(['balance' => $newBalance]);


        // save them again , take money form 
        $bankAccount = BankAccount::find($request->account_id);
        $bankAccount->update([
            'balance' => $bankAccount->balance - $disccountFormAccount
        ]);

        // send money to 
        $transfareTo = BankAccount::find($request->receiver_id);
        ;
        $transfareTo->update([
            'balance' => $transfareTo->balance + $amount
        ]);


        return response()->json();
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

        return back()->withSuccess(__('dashboard.success'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'account_id' => 'required|exists:bank_accounts,id',
            'price' => 'required|numeric',
            'payment_method' => 'required|string',
            'statement' => 'required',
            'notes' => 'nullable|string',
        ]);

        $bankAccount = BankAccount::findOrFail($request->account_id);
        $payment = Payment::create($validatedData);

        $bankAccount->update([
            'balance' => $bankAccount->balance + $request->price
        ]);

        return back()->withSuccess(__('dashboard.success'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show($order)
    {
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
        $validatedData = $request->validate([
            'price' => 'required|numeric',
            'account_id' => 'required|exists:bank_accounts,id',
            'payment_method' => 'required|string',
            'statement' => 'required',
            'notes' => 'nullable|string',
        ]);

        $oldBankAccount = BankAccount::find($payment->account_id);
        //  return money back 
        $oldBankAccount->update([
            'balance' => $oldBankAccount->balance - $payment->price
        ]);

        $payment->update($validatedData);


        $bankAccount = BankAccount::findOrFail($request->account_id);
        // take money form bank 
        $bankAccount->update([
            'balance' => $bankAccount->balance + $request->price
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

        $bankAccount = BankAccount::find($payment->account_id);
        $bankAccount->update([
            'balance' => $bankAccount->balance - $payment->price
        ]);

        $payment->delete();
        return response()->json();
    }


    public function deleteAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (Payment::whereIn('id', $ids)->delete()) {
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }
}
