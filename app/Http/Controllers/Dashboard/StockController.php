<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceReport;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\StockAdjustment;

class StockController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Stock::class);

        // quantity fillter
        $filters = [
            'quantity_min' => request()->query('quantity_min'),
            'quantity_max' => request()->query('quantity_max'),
            'quantity' => request()->query('quantity'),
            'higher_selling' => request()->query('higher_selling'),
        ];

        $stocks = Stock::filter($filters)
            ->get();

        $lowStock = $stocks->filter(function ($stock) {
            return $stock->quantity < 6;
        });
        return view('dashboard.stocks.index', compact('stocks', 'lowStock'));
    }

    public function create()
    {
        $this->authorize('create', Stock::class);
        return view('dashboard.stocks.create');
    }


    public function store(Request $request)
    {
        $this->authorize('create', Stock::class);

        try {
            // Validate the input data
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'description' => 'nullable',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
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
        $this->authorize('view', $stock);
        $orders = $stock->orders()->paginate(100);


        return view('dashboard.stocks.show', compact('stock', 'orders'));
    }

    public function edit($stock)
    {
        $stock = Stock::findOrFail($stock);
        $this->authorize('update', $stock);
        return view('dashboard.stocks.create', compact('stock'));
    }

    public function update(Request $request, $stock)
    {
        $stock = Stock::findOrFail($stock);
        $this->authorize('update', $stock);
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
        $this->authorize('delete', $stock);
        $stock->delete();
        return response()->json();
    }


    public function deleteAll(Request $request)
    {
        $this->authorize('delete', Stock::class);

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

    public function destroyServiceStock(Service $service, Stock $stock)
    {
        $attached = $service->stocks()->whereKey($stock->id)->first();
        if (!$attached) {
            return response()->json(['error' => __('dashboard.stock_not_attached')], 422);
        }

        $count = (int) $attached->pivot->count;

        \DB::transaction(function () use ($service, $stock, $count) {
            $stock->increment('quantity', $count);
            $service->stocks()->detach($stock->id);
        });

        return response()->json(['message' => __('dashboard.success')], 200);
    }

    public function destroyServiceReport(ServiceReport $report)
    {
        $report->delete();
        return response()->json(['message' => __('dashboard.success')], 200);
    }

    public function getAvailableStocks()
    {
        $stocks = Stock::select('id', 'name', 'quantity')->where('quantity', '>', 0)->get();
        return response()->json($stocks);
    }

    public function stockReport(Stock $stock)
    {
        // $this->authorize('view', $stock);

        $transactions = $stock->stockAdjustments()->where("verified", true)->paginate(10) ;

        return view('dashboard.stocks.stockReport', compact('stock', 'transactions'));
    }

}
