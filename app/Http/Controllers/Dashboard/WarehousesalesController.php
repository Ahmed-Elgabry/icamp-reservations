<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\{Order , OrderItem, Stock};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            \DB::beginTransaction();
            Order::find($data['order_id'])->items()->create($data);
            $stock = Stock::findOrFail($data['stock_id']);
            if ($stock->quantity < $data['quantity'])
                throw new \Exception(__('dashboard.insufficient_stock'));
            $stock->decrement('quantity', $data['quantity']);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
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

        return redirect()->back()->with('success', __('dashboard.item_updated_successfully'));
    }

    public function destroy($id)
    {
        $item = OrderItem::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', __('dashboard.item_deleted_successfully'));
    }
}
