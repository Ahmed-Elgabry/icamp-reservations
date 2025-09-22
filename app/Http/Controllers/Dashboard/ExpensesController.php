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
        $this->authorize('viewAny', Expense::class);

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
        $expenseItems = ExpenseItem::orderBy('created_at', 'desc')->get();

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

        // Get recent expenses (last 10 expenses)
        $recentExpenses = Expense::with(['expenseItem', 'account'])
            ->whereHas('transaction', function ($q) {
                $q->where('source', 'general_expenses');
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.expenses.create', compact('expenseItems', 'bankAccounts', 'recentExpenses'));
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
            'payment_method' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'date' => 'nullable|date',
            'order_id' => 'nullable|exists:orders,id',
            'notes' => 'nullable|string|max:1000',
            'source' => 'required|string',
        ]);


        DB::beginTransaction();

        try {
            $date = $request->date ? $request->date : date('Y-m-d');
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('expenses', 'public');
            }

            $expense = Expense::create([
                'expense_item_id' => $request->expense_item_id,
                'account_id' => $request->account_id,
                'price' => $request->price,
                'payment_method' => $request->payment_method,
                'date' => $date,
                'statement' => $request->statement ?? 'general_expenses',
                'notes' => $request->notes,
                'image' => $imagePath, // Store as 'image' field
                'image_path' => $imagePath, // Also store as 'image_path' for compatibility
                'order_id' => $request->order_id,
                'source' => $request->source
            ]);
            Transaction::create([
                'account_id' => $request->account_id,
                'amount' => $request->price,
                'order_id' => $request->order_id,
                'type' => 'debit',
                'date' => $date,
                'description' => 'Expense: ' . ($request->statement ?? 'general_expenses'),
                'source' => $request->source,
                'expense_id' => $expense->id
            ]);

            DB::commit();

            // If it's an AJAX/JSON request, return JSON to satisfy front-end expectations
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('dashboard.expense_created_successfully')
                ], 200);
            }

            return redirect()->back()->with('success', __('dashboard.expense_created_successfully'));
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Expense store transaction failed: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Expense creation failed'], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Expense creation failed: ' . $e->getMessage()]);
        }
    }


    public function edit($expense)
    {
        $expense = Expense::findOrFail($expense);
        $this->authorize('update', $expense);

        $expenseItems = ExpenseItem::all();
        $bankAccounts = BankAccount::all();

        // Get recent expenses (last 10 expenses)
        $recentExpenses = Expense::with(["transaction", 'expenseItem', 'account'])
            ->whereHas('transaction', function ($q) {
                $q->where('source', 'general_expenses');
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.expenses.create', compact('expenseItems', 'expense', 'bankAccounts', 'recentExpenses'));
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
            'payment_method' => 'required|string',
            'source' => 'required|string',
            'statement' => 'nullable|string',
            'date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $data['date'] = $request->date ? $request->date : $expense->date;

            // Handle bank account balance update if expense is verified
            if ($expense->verified) {
                $bankAccount = BankAccount::findOrFail($request->account_id);
                // Calculate the difference and update the balance accordingly
                $bankAccount->update([
                    'balance' => ($bankAccount->balance + $expense->price)
                ]);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($expense->image && \Storage::disk('public')->exists($expense->image)) {
                    \Storage::disk('public')->delete($expense->image);
                }
                if ($expense->image_path && \Storage::disk('public')->exists($expense->image_path)) {
                    \Storage::disk('public')->delete($expense->image_path);
                }
                // Store new image
                $imagePath = $request->file('image')->store('expenses', 'public');
                $data['image'] = $imagePath;
                $data['image_path'] = $imagePath;
            }
            $data["verified"] = 0; // Reset verified status on update
            $expense->update($data);

            Transaction::where('expense_id', $expense->id)->update([
                'account_id' => $request->account_id,
                'amount' => $request->price,
                'type' => 'debit',
                'date' => $data['date'],
                'description' => 'Expense: ' . ($request->statement ?? 'general_expenses'),
                'source' => $request->source,
            ]);
            DB::commit();

            // If it's an AJAX/JSON request, return JSON to satisfy front-end expectations
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('dashboard.expense_updated_successfully')
                ], 200);
            }

            return redirect()->back()->with('success', __('dashboard.expense_updated_successfully'));
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Expense update transaction failed: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Expense update failed'], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Expense update failed: ' . $e->getMessage()]);
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
            if ($expense->verified) {
                $bankAccount = BankAccount::findOrFail($expense->account_id);
                $bankAccount->increment('balance', $expense->price);
            }

            // Delete image files if they exist
            if ($expense->image && \Storage::disk('public')->exists($expense->image)) {
                \Storage::disk('public')->delete($expense->image);
            }
            if ($expense->image_path && \Storage::disk('public')->exists($expense->image_path)) {
                \Storage::disk('public')->delete($expense->image_path);
            }

            $expense->delete();

            DB::commit();
            return response()->json(["success" => true, "deleted_expense_amount" => $expense->price]);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Expense destroy transaction failed: ' . $e->getMessage());
            return response()->json(["success" => false, "message" => "Delete failed"], 500);
        }
    }

    /**
     * Download expense image attachment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadImage($id)
    {
        $expense = Expense::findOrFail($id);

        // Check if user has permission to view this expense
        $this->authorize('view', $expense);

        $imagePath = $expense->image ?? $expense->image_path;

        if (!$imagePath) {
            abort(404, 'Image not found');
        }

        $filePath = storage_path('app/public/' . $imagePath);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath);
    }
}
