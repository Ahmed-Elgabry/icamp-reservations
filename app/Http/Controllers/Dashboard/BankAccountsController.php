<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class BankAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = BankAccount::orderBy('created_at','desc')->get();
        return view('dashboard.banks.index',[
            'banks' => $banks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.banks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'account_number' => 'nullable|unique:bank_accounts',
            'name' => 'required',
            'balance' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $bankAccount = BankAccount::create($validatedData);

        return response()->json(['message' => 'Bank account created successfully', 'bank_account' => $bankAccount]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show( $bankAccount)
    {
        $bank = BankAccount::findOrFail($bankAccount);
        $startDate = request('start_date');
        $endDate = request('end_date');

        $transactions = Transaction::where(function ($query) use ($bank) {
                $query->where('account_id', $bank->id)
                      ->orWhere('receiver_id', $bank->id)
                      ->orWhere('sender_account_id', $bank->id);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // $expenses = Expense::where('account_id', $bank->id)
        //     ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
        //         return $query->whereBetween('date', [$startDate, $endDate]);
        //     })
        //     ->orderBy('created_at', 'desc')
        //     ->get();
        // $expenses = Expense::where('account_id', $bank->id)
        // ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
        //     return $query->whereBetween('date', [$startDate, $endDate]);
        // })
        // ->orderBy('created_at', 'desc')
        // ->get();

        // $payments = Payment::where(function ($query) use ($bank) {
        //         $query->where('account_id', $bank->id)
        //               ->Where('verified', false);
        //     })
        //     ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
        //         return $query->whereBetween('date', [$startDate, $endDate]);
        //     })
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        //     // Convert transactions and expenses to a unified collection
        // $merged = collect();

        // foreach ($transactions as $transaction) {
        //     $merged->push((object)[
        //         'account' => $transaction->account,
        //         'receiver' => $transaction->receiver,
        //         'order_id' => $transaction->order_id,
        //         'id' => $transaction->id,
        //         'date' => $transaction->date,
        //         'editRoute' => route('bank-accounts.edit', $transaction->id),
        //         'destroyRoute' => route('transactions.destroy', $transaction->id),
        //         'type' => 'Bank-account',
        //         'amount' => $transaction->amount,
        //         'description' => $transaction->description,
        //         'created_at' => $transaction->created_at,
        //     ]);
        // }

        // foreach ($expenses as $expense) {
        //     $merged->push((object)[
        //         'account' => $expense->account,
        //         'receiver' => null,
        //         'order_id' => null,
        //         'editRoute' => route('expenses.edit', $expense->id),
        //         'destroyRoute' => route('expenses.destroy', $expense->id),
        //         'id' => $expense->id,
        //         'date' => $expense->date,
        //         'type' => 'Expense',
        //         'amount' => $expense->price,
        //         'description' => $expense->notes,
        //         'created_at' => $expense->created_at,
        //     ]);
        // }

        // foreach ($payments as $payment) {
        //     $merged->push((object)[
        //         'account' => $payment->account,
        //         'receiver' => null,
        //         'order_id' => $payment->order_id,
        //         'editRoute' => route('payments.show', $payment->order_id),
        //         'destroyRoute' => route('payments.destroy', $payment->id),
        //         'id' => $payment->id,
        //         'date' => $payment->created_at,
        //         'type' => 'Payment',
        //         'amount' => $payment->price,
        //         'description' => $payment->notes,
        //         'created_at' => $payment->created_at,
        //     ]);
        // }

        // Sort the merged collection by created_at
        // $merged = $merged->sortByDesc('created_at');

        // Manually paginate the merged collection
        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10; // Set your desired items per page
        $currentResults = $transactions->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedResults = new LengthAwarePaginator($currentResults, $transactions->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        // Append query parameters to pagination links
        $paginatedResults->appends(request()->all());

        return view('dashboard.banks.show',[
            'bank' => $bank,
            'transactions' => $paginatedResults
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function edit( $bankAccount)
    {
        $bank = BankAccount::findOrFail($bankAccount);
        return view('dashboard.banks.create',[
            'bank' => $bank
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $bankAccount)
    {
        $bankAccount = BankAccount::findOrFail($bankAccount);

        $validatedData = $request->validate([
            'account_number' => 'nullable|unique:bank_accounts,account_number,' . $bankAccount->id,
            'name' => 'required',
            'notes' => 'nullable|string',
        ]);

        $bankAccount->update($validatedData);

        return response()->json(['message' => 'Bank account created successfully', 'bank_account' => $bankAccount]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy( $bankAccount)
    {
        $bank = BankAccount::findOrFail($bankAccount);
        $bank->delete();
        return response()->json();
    }
}
