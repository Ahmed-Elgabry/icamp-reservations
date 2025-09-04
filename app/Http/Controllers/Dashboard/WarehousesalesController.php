<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\{Order , OrderItem, Stock};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use DB;

class WarehousesalesController extends Controller
{
    public function show($order)
    {
        $order = Order::findOrFail($order);

        return view('dashboard.warehouse_sales.show', [
            'order' => $order,
            'stocks' => Stock::select('id' , 'selling_price', 'name')->get(),
            'items' => $order->items()->with('stock')->get(),
            'bankAccounts' => \App\Models\BankAccount::pluck('name','id'),
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
            $orderItem = $order->items()->create($data);
            \App\Models\BankAccount::findOrFail($data['account_id'])->increment('balance', $data['total_price']);
            Transaction::create([
                'account_id' => $data['account_id'],
                'amount' => $data['total_price'],
                'description' => $data['notes'],
                'order_id' => $data['order_id'],
                "type" =>"deposit",
                'source' => 'warehouse_sale',
                "stock_id" => $data['stock_id'],
                'order_item_id' => $orderItem->id,
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
            if ($stock->quantity + $item->quantity < $data['quantity']) {
                throw new \Exception(__('dashboard.insufficient_stock'));
            }
            \App\Models\BankAccount::findOrFail($data['account_id'])->increment('balance', ($data['total_price'] - $item->total_price));
            $stock->increment('quantity', $item->quantity - $data['quantity']);
            $item->update($data);
            Transaction::where('order_item_id', $item->id)->update([
                'account_id' => $data['account_id'],
                'amount' => $data['total_price'],
                'description' => $data['notes'],
                "type" =>"deposit",
                'source' => 'warehouse_sale',
                "stock_id" => $data['stock_id'],
            ]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Warehouse sale update transaction failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', __('dashboard.item_updated_successfully'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $item = OrderItem::findOrFail($id);
            $stock = Stock::findOrFail($item->stock_id);
            
            // Return stock quantity and reverse bank account balance
            $stock->increment('quantity', $item->quantity);
            \App\Models\BankAccount::findOrFail($item->account_id)->decrement('balance', $item->total_price);
            
            // Delete related transaction
            Transaction::where('order_item_id', $item->id)->delete();
            
            $item->delete();
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Warehouse sale destroy transaction failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Delete failed. Please try again.']);
        }

        return redirect()->back()->with('success', __('dashboard.item_deleted_successfully'));
    }
}
