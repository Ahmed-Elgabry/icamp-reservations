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
        $this->authorize('view', Expense::class);

        $expenses = Expense::where('order_id', $id)->orderBy('created_at', 'desc')->get();
        $order = Order::findOrFail($id);
        $bankAccounts = BankAccount::all();

        return view('dashboard.expenses.show', compact('expenses', 'order', 'bankAccounts'));
    }

    public function export()
    {
        $this->authorize('export', Expense::class);

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
        $this->authorize('viewAny', Expense::class);

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

        $expenses = $query->where(
            fn($q) =>
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
        $this->authorize('create', Expense::class);

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
        $this->authorize('create', Expense::class);

        $validated = $request->validate([
            'expense_item_id' => 'nullable|exists:expense_items,id',
            'account_id' => 'required|exists:bank_accounts,id',
            'price' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'image' => 'nullable|image',
            'date' => 'nullable|date',
            'order_id' => 'nullable|exists:orders,id',
            'notes' => 'nullable|string',
        ]);

        $date = $request->date ? $request->date : date('y-m-d');
        $bankAccount = BankAccount::findOrFail($request->account_id);
        $bankAccount->update([
            'balance' => $bankAccount->balance - $request->price
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('expenses', 'public');
        }

        Expense::create([
            'expense_item_id' => $request->expense_item_id,
            'account_id' => $request->account_id,
            'price' => $request->price,
            'payment_method' => $request->payment_method,
            'date' => $date,
            'notes' => $request->notes,
            'image' => $path ?? null,
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
        $expense = Expense::findOrFail($expense);
        $this->authorize('update', $expense);

        $expenseItems = ExpenseItem::all();
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
        $this->authorize('update', $expense);
        $data = $request->validate([
            'expense_item_id' => 'nullable|exists:expense_items,id',
            'account_id' => 'required|exists:bank_accounts,id',
            'price' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'date' => 'nullable|date',
            'image' => 'nullable|image',
            'notes' => 'nullable|string',
        ]);
        $data['date'] = $request->date ? $request->date : $expense->date;
        $bankAccount = BankAccount::findOrFail($request->account_id);
        $bankAccount->update([
            'balance' => $bankAccount->balance + $expense->price
        ]);
        $bankAccount->update([
            'balance' => $bankAccount->balance - $request->price
        ]);
        if ($request->hasFile('image')) {
            if ($expense->image) {
                \Storage::disk('public')->delete($expense->image);
            }
            $data['image'] = $request->file('image')->store('expenses', 'public');
        }
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
        $this->authorize('delete', $expense);
        $expense->delete();
        return response()->json();
    }
}
