<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseItem;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Exports\ExpensesExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use DB;


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
        $request = request();
        $query = Expense::with('expenseItem');

        // إعداد التواريخ
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $expenseItemId = $request->input('expense_item_id');

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

        // تطبيق فلتر بند المصاريف
        if ($expenseItemId) {
            $query->where('expense_item_id', $expenseItemId);
        }

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

        // جلب جميع بنود المصاريف للفلتر
        $expenseItems = ExpenseItem::orderBy('created_at','desc')->get();

        return view('dashboard.expenses.index', [
            'expenses' => $expenses,
            'expenseItems' => $expenseItems,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'expense_item_id' => $expenseItemId
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

        DB::beginTransaction();
        
        try {
            $date = $request->date ? $request->date : date('y-m-d');
            $bankAccount = BankAccount::findOrFail($request->account_id);
            if($request->statement !== "reservation_expenses"){
                $bankAccount->update([
                    'balance' => $bankAccount->balance - $request->price
                ]);
            }

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('expenses', 'public');
            }

            $expense = Expense::create([
                'expense_item_id' => $request->expense_item_id,
                'account_id' => $request->account_id,
                'price' => $request->price,
                'payment_method' => $request->payment_method,
                'date' => $date,
                'statement' => $request->statement,
                'notes' => $request->notes,
                'image' => $path ?? null,
                'order_id' => $request->order_id,
                'source' => $request->source,
            ]);
            
            Transaction::create([
                'account_id' => $request->account_id,
                'amount' => $request->price,
                'order_id' => $request->order_id,
                'type' => 'debit',
                'date' => $date,
                'description' => 'Expense: ' . $request->statement,
                'source' => $request->source,
                "expense_id"=>$expense->id
            ]);

            DB::commit();

            // that mean he is get form orders page
            if (!$request->date) {
                return back()->withSuccess(__('dashboard.success'));
            }

            return response()->json(['message' => 'Expense created successfully']);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Expense store transaction failed: ' . $e->getMessage());
            
            if (!$request->date) {
                return back()->withErrors(['error' => 'Expense creation failed']);
            }
            
            return response()->json(['message' => 'Expense creation failed'], 500);
        }
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
            'source' => 'required|string',
            'statement' => 'nullable|string',
            'date' => 'nullable|date',
            'image' => 'nullable|image',
            'notes' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            $data['date'] = $request->date ? $request->date : $expense->date;
            if ($request->statement !== "reservation_expenses") {
                $bankAccount = BankAccount::findOrFail($request->account_id);
                $bankAccount->update([
                    'balance' => ($bankAccount->balance + $expense->price) - $data['price']
                ]);
            }            
            if ($request->hasFile('image')) {
                if ($expense->image) {
                    \Storage::disk('public')->delete($expense->image);
                }
                $data['image'] = $request->file('image')->store('expenses', 'public');
            }
            
            $expense->update($data);
            
            Transaction::where('expense_id', $expense->id)->update([
                'account_id' => $request->account_id,
                'amount' => $request->price,
                'type' => 'debit',
                'date' => $data['date'],
                'description' => 'Expense: ' . $request->statement,
                'source' => $request->source,
            ]);
            
            DB::commit();
            
            if (!$request->date) {
                return back()->withSuccess(__('dashboard.success'));
            }
            
            return response()->json(['message' => 'Expense updated successfully']);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Expense update transaction failed: ' . $e->getMessage());
            
            if (!$request->date) {
                return back()->withErrors(['error' => 'Expense update failed']);
            }
            
            return response()->json(['message' => 'Expense update failed'], 500);
        }
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
        
        DB::beginTransaction();
        
        try {
            if($expense->statement !== "reservation_expenses"){
                $bankAccount = BankAccount::findOrFail($expense->account_id);
                $bankAccount->increment('balance', $expense->price);
            }

            $expense->delete();
            
            DB::commit();
            return response()->json(["success" => true,"deleted_expense_amount" => $expense->price]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Expense destroy transaction failed: ' . $e->getMessage());
            return response()->json(["success" => false, "message" => "Delete failed"], 500);
        }
    }
}
