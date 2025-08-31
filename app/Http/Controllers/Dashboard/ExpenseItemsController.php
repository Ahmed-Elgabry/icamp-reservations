<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ExpenseItem;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseItemsController extends Controller
{
    // List all expense items
    public function index()
    {
        $this->authorize('viewAny', ExpenseItem::class);

        $expenseItems = ExpenseItem::orderBy('created_at','desc')->get();

        return view('dashboard.expense_items.index', compact('expenseItems'));
    }



    // Show form to create new expense item
    public function create()
    {
        $expenseItems = ExpenseItem::orderBy('created_at','desc')->get();
        return view('dashboard.expense_items.create', compact('expenseItems'));
    }

    // Store new expense item
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_items,name',
            'description' => 'nullable|string|max:255',
        ]);

        ExpenseItem::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

       return response()->json();
    }

    public function show($id)
    {
        $request = request();
        $expenseItem = ExpenseItem::findOrFail($id);

        // Calculate total expenses
        $totalExpenses = $expenseItem->expenses->sum(function ($category) {
            return $category->sum('price');
        });

        // Calculate current month's expenses
        $currentMonthExpenses = $expenseItem->expenses->sum(function ($category) {
            return $category->where('created_at', '>=', now()->startOfMonth())->sum('price');
        });


        $query = Expense::query();

        // Filter by date
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $query->where('expense_item_id', $expenseItem->id);

        $expenses = $query->orderBy('created_at','desc')->paginate(100);

        return view('dashboard.expense_items.show', compact('expenseItem', 'totalExpenses', 'currentMonthExpenses','expenses'));
    }


    // Show form to edit expense item
    public function edit( $expenseItem)
    {
        $expenseItem = ExpenseItem::findOrFail($expenseItem);
        $expenseItems = ExpenseItem::orderBy('created_at','desc')->get();

        return view('dashboard.expense_items.create', compact('expenseItem','expenseItems'));
    }

    // Update existing expense item
    public function update(Request $request,  $expenseItem)
    {
        $expenseItem = ExpenseItem::findOrFail($expenseItem);

        $request->validate([
            'name' => 'required|string|max:255|unique:expense_items,name,' . $expenseItem->id,
            'description' => 'nullable|string|max:255',
        ]);

        $expenseItem->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json();
    }

    // Delete expense item
    public function destroy( $expenseItem)
    {
        $expenseItem = ExpenseItem::findOrFail($expenseItem);
        $expenseItem->delete();
       return response()->json();
    }
}
