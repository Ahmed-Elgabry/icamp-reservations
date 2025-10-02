<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\{Order , OrderItem, Stock};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\BankAccount;
use DB;

class WarehousesalesController extends Controller
{
    public function show($order)
    {
        $order = Order::findOrFail($order);

        return view('dashboard.warehouse_sales.show', [
            'order' => $order,
            'stocks' => Stock::latest()->get(),
            'items' => $order->items()->with('stock')->latest()->get(),
            'bankAccounts' => BankAccount::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'stock_id' => 'required|exists:stocks,id',
            'quantity' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'account_id' => 'required|exists:bank_accounts,id',
            'total_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();

        try {
            // Create item via the order relation so order_id is set consistently
            $order = Order::findOrFail($data['order_id']);
            $stock = Stock::findOrFail($data['stock_id']);
            // Add handled_by to the data
            $data['handled_by'] = auth()->id();
            $orderItem = $order->items()->create($data);
            
            Transaction::create([
                'account_id' => $data['account_id'],
                'amount' => $data['total_price'],
                'description' => $data['notes'],
                'order_id' => $data['order_id'],
                'handled_by' => auth()->id(),
                "type" =>"deposit",
                'source' => 'warehouse_sale',
                "stock_id" => $data['stock_id'],
                'order_item_id' => $orderItem->id,
            ]);
              // create snapshot record before mutating
              StockAdjustment::create([
                'available_quantity_before' => $stock->quantity,
                'available_quantity_after' => $stock->quantity - $data['quantity'],
                'stock_id' => $data['stock_id'],
                'quantity' => isset($data['quantity']) ? $data['quantity'] : null,
                'order_item_id' => $orderItem->id,
                "reason" => "for_warehouse_sale",
                'type' => 'item_decrement',
                'order_id' => $data['order_id'],
                'source' => 'Warehouse Sale',
                'percentage' => isset($stock->percentage) ? $stock->percentage - $data['quantity'] : null,
                'available_percentage_before' => $stock->percentage,
                'verified' => 0,
                'date_time' => now(),
                'employee_name' => auth()->user()->name ?? null,
            ]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Warehouse sale store transaction failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', __('dashboard.item_added_successfully'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantity' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'account_id' => 'required|exists:bank_accounts,id',
            'notes' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();

        try {
            $item = OrderItem::findOrFail($id);
            $stock = Stock::findOrFail($item->stock_id);
            if ($item->verified) {
                // Return previous quantity to stock before deducting new quantity
                $stock->increment('quantity', $item->quantity);
                BankAccount::findOrFail($item->account_id)->decrement('balance', $item->total_price);
            }
            $data["verified"] = 0 ; // Reset verified status on update
            $item->update($data);
            Transaction::where('order_item_id', $item->id)->update([
                'account_id' => $data['account_id'],
                'amount' => $data['total_price'],
                'description' => $data['notes'],
                "type" =>"deposit",
                'source' => 'warehouse_sale',
                "stock_id" => $data['stock_id'],
                'verified' => 0
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Warehouse sale update transaction failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', __('dashboard.deleted_successfully'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $item = OrderItem::findOrFail($id);
            $stock = Stock::findOrFail($item->stock_id);

            // Return stock quantity and reverse bank account balance
            if ($item->verified) {
                $stock->increment('quantity', $item->quantity);
                BankAccount::findOrFail($item->account_id)->decrement('balance', $item->total_price);
            }

            // Delete related transaction
            Transaction::where('order_item_id', $item->id)->delete();

            $item->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Warehouse sale destroy transaction failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Delete failed. Please try again.']);
        }

        return redirect()->back()->with('success', __('dashboard.success'));
    }
}
