<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockQuantity;
use App\Models\Stock;


class StockQuantitiesController extends Controller
{
    
    public function show($stock)
    {
        $stock = Stock::findOrFail($stock);
        $StockQuantity = StockQuantity::where('stock_id',$stock->id)->orderBy('created_at','desc')->paginate(100);

        return view('dashboard.stocks.quantity',[
            'StockQuantity' => $StockQuantity,
            'stock' => $stock
        ]);
    }


    public function store(Request $request, $stockId)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer',
            'price' => 'required',
            'notes' => 'nullable|string'
        ]);

        $stock = Stock::findOrFail($stockId);
        $stock->quantities()->create($validatedData);

        $stock->update([
            'quantity' => $stock->quantity +  $request->quantity,
            'price' => $stock->price + $request->price
        ]);

        return back()->withSuccess(__('dashboard.success'));
    }

    // Update an existing stock quantity
    public function update(Request $request,$quantityId)
    {
        $quantity = StockQuantity::findOrFail($quantityId);

        $validatedData = $request->validate([
            'quantity' => 'required|integer',
            'notes' => 'nullable|string'
        ]);

        $stock = Stock::findOrFail($quantity->stock_id);

        $stock->update([
            'quantity' => $stock->quantity -  $quantity->quantity,
            'price' => $stock->price -  $quantity->price,
        ]);

        $quantity->update($validatedData);

        $stock->update([
            'quantity' => $stock->quantity +  $request->quantity,
            'price' => $stock->price +  $request->price,
        ]);

        return back()->withSuccess(__('dashboard.success'));
    }

    // Delete a stock quantity
    public function destroy($quantityId)
    {
        $quantity = StockQuantity::findOrFail($quantityId);
        $stock = Stock::findOrFail($quantity->stock_id);


        $stock->update([
            'quantity' => $stock->quantity -  $quantity->quantity,
            'price' => $stock->price -  $quantity->price,
        ]);

        $quantity->delete();

        return response()->json();
    }



}
