<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseItem;
use App\Models\Order;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Exports\ExpensesExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;


class ExpensesController extends Controller
{
    public function show($id)
    {
        $expenses = Expense::where('order_id', $id)->orderBy('created_at', 'desc')->get();
        $order = Order::findOrFail($id);
        $bankAccounts = BankAccount::all();

        return view('dashboard.expenses.show', compact('expenses', 'order', 'bankAccounts'));
    }

    public function export()
    {
        $expenseItem = request('expenseItem');
        $fileName = date('y-m-d') . "- المصاريف";

        return Excel::download(new ExpensesExport($expenseItem), $fileName . '.xlsx');
    }

    // دالة للتحقق من صحة التاريخ
    private function isValidDate($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date) !== false;
    }

    public function index()
    {
        $request = request();
        $query = Expense::query();

        // إعداد التواريخ
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // التحقق من صحة التواريخ
        if ($startDate && !$this->isValidDate($startDate)) {
            return redirect()->back()->withErrors(['start_date' => 'تاريخ البدء غير صحيح.']);
        }

        if ($endDate && !$this->isValidDate($endDate)) {
            return redirect()->back()->withErrors(['end_date' => 'تاريخ النهاية غير صحيح.']);
        }

        // تحويل التواريخ إلى كائنات Carbon إذا كانت صحيحة
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        // تطبيق فلتر تاريخ إذا تم توفير التاريخين
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $expenses = $query->where( fn($q) =>
            $q->where('verified', true)
               ->orWhereNull('verified')
        )
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('dashboard.expenses.index', [
            'expenses' => $expenses,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expenseItems = ExpenseItem::all();
        $bankAccounts = BankAccount::all();

        return view('dashboard.expenses.create', compact('expenseItems', 'bankAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_item_id' => 'nullable|exists:expense_items,id',
            'account_id' => 'required|exists:bank_accounts,id',
            'price' => 'required|numeric|min:0',
            'date' => 'nullable|date',
            'order_id' => 'nullable|exists:orders,id',
            'notes' => 'nullable|string',
        ]);

        $date = $request->date ? $request->date : date('y-m-d');
        $bankAccount = BankAccount::findOrFail($request->account_id);
        $bankAccount->update([
            'balance' => $bankAccount->balance - $request->price
        ]);

        // Create the expense record
        Expense::create([
            'expense_item_id' => $request->expense_item_id,
            'account_id' => $request->account_id,
            'price' => $request->price,
            'date' => $date,
            'notes' => $request->notes,
            'order_id' => $request->order_id,
        ]);


        // that mean he is get form orders page
        if (!$request->date) {
            return back()->withSuccess(__('dashboard.success'));
        }

        return response()->json();
    }


    public function edit($expense)
    {
        $expenseItems = ExpenseItem::all();
        $expense = Expense::findOrFail($expense);
        $bankAccounts = BankAccount::all();

        return view('dashboard.expenses.create', compact('expenseItems', 'expense', 'bankAccounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $expense)
    {
        $expense = Expense::findOrFail($expense);

        $data = $request->validate([
            'expense_item_id' => 'nullable|exists:expense_items,id',
            'account_id' => 'required|exists:bank_accounts,id',
            'price' => 'required|numeric|min:0',
            'date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $data['date'] = $request->date ? $request->date : $expense->date;

        $oldBankAccount = BankAccount::find($expense->account_id);
        $bankAccount = BankAccount::findOrFail($request->account_id);
        //  return money back
        $bankAccount->update([
            'balance' => $bankAccount->balance + $expense->price
        ]);

        // take money form bank
        $bankAccount->update([
            'balance' => $bankAccount->balance - $request->price
        ]);

        $expense->update($data);

        if (!$request->date) {
            return back()->withSuccess(__('dashboard.success'));
        }

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy($expense)
    {
        $expense = Expense::findOrFail($expense);
        // check account to take money of
        $bankAccount = BankAccount::find($expense->account_id);
        $bankAccount->update([
            'balance' => $bankAccount->balance + $expense->price
        ]);

        $expense->delete();
        return response()->json();
    }


}
