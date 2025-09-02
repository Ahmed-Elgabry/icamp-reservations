<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\{Order , OrderItem, Stock};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

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

        try {
                // Create item via the order relation so order_id is set consistently
            $order = Order::findOrFail($data['order_id']);
            $stock = Stock::findOrFail($data['stock_id']);
            if ($stock->quantity < $data['quantity'])
                throw new \Exception(__('dashboard.insufficient_stock'));
            $stock->decrement('quantity', $data['quantity']);
            $orderItem = $order->items()->create($data);
            // Ensure we have the generated primary key loaded before using it
                \Log::info(['order_item_id' => $orderItem->id]);
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
        } catch (\Exception $e) {
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

        $item = OrderItem::findOrFail($id);
        $stock = Stock::findOrFail($item->stock_id);
        if ($stock->quantity + $item->quantity > $data['quantity']) {
            return redirect()->back()->withErrors(['error' => __('dashboard.insufficient_stock')]);
        }
        $stock->increment('quantity',$data['quantity']);
        $item->update($data);
        // Update the exact transaction linked to this item
        Transaction::where('order_item_id', $item->id)->update([
            'account_id' => $data['account_id'],
            'amount' => $data['total_price'],
            'description' => $data['notes'],
            "type" =>"deposit",
            'source' => 'warehouse_sale',
            "stock_id" => $data['stock_id'],
        ]);

        return redirect()->back()->with('success', __('dashboard.item_updated_successfully'));
    }

    public function destroy($id)
    {
        $item = OrderItem::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', __('dashboard.item_deleted_successfully'));
    }
}
