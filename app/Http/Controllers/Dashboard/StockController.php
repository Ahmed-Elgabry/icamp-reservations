<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        // quantity fillter
        $filters = [
            'quantity_min' => request()->query('quantity_min'),
            'quantity_max' => request()->query('quantity_max'),
            'quantity' => request()->query('quantity'),
        ];

        $stocks = Stock::filter($filters)
            ->orderBy('created_at', 'desc')
            ->get();

        $lowStock = $stocks->filter(function ($stock) {
            return $stock->quantity < 6;
        });
        return view('dashboard.stocks.index', compact('stocks', 'lowStock'));
    }

    public function create()
    {
        return view('dashboard.stocks.create');
    }


    public function store(Request $request)
    {
        try {
            // Validate the input data
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'description' => 'nullable',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'price' => 'required|numeric',
                'selling_price' => 'nullable|numeric',
                'quantity' => 'nullable|integer',
                'percentage' => 'nullable|string'
            ]);

            $stock = new Stock($validatedData);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file->isValid()) {
                    $stock->image = $file;
                } else {
                    return response()->json(['error' => 'Invalid image file.'], 422);
                }
            }

            $stock->save();
            return response()->json(['success' => 'Stock created successfully']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function show($stock)
    {
        $stock = Stock::with('orders')->findOrFail($stock);
        $orders = $stock->orders()->paginate(100);


        return view('dashboard.stocks.show', compact('stock', 'orders'));
    }

    public function edit($stock)
    {
        $stock = Stock::findOrFail($stock);
        return view('dashboard.stocks.create', compact('stock'));
    }

    public function update(Request $request, $stock)
    {
        $stock = Stock::findOrFail($stock);
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'nullable|image',
            'price' => 'required',
            'selling_price' => 'nullable|numeric',
            'quantity' => 'nullable|integer',
            'percentage' => 'nullable|string'

        ]);


        $stock->update($validatedData);
        return response()->json();
    }

    public function destroy($stock)
    {
        $stock = Stock::findOrFail($stock);
        $stock->delete();
        return response()->json();
    }


    public function deleteAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (Stock::whereIn('id', $ids)->delete()) {
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }

    public function destroyServiceStock(Service $service, $pivot)
    {
        // $deleted = \DB::table('service_stock')
        //     ->where('id', $pivot)
        //     ->where('service_id', $service->id)
        //     ->delete();

        // if ($deleted) {
        //     return back()->with('success', __('dashboard.stock_removed_from_service'));
        // }
        // return back()->with('error', __('dashboard.error_removing_stock_from_service'));
    }

}
