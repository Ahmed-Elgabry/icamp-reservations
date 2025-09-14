<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{

    public function issuedItemsIndex() {
        $issuedStocks = StockAdjustment::with('stock')
            ->where('type', 'item_decrement')
            ->latest()->paginate(10);
        return view('dashboard.services.manual_item_withdrawal_and_return', compact('issuedStocks'));
    }
    public function returnedItemsIndex(){
        $returnedStocks = StockAdjustment::with('stock')
            ->where('type', 'item_increment')
            ->latest()
            ->paginate(10);

         return view('dashboard.services.manual_item_withdrawal_and_return', compact('returnedStocks'));
    }
    public function create()
    {
        $stocks = \App\Models\Stock::all();
        $orders = \App\Models\Order::all();
        return view('dashboard.services.manual_item_withdrawal_and_return', compact('stocks', 'orders'));
    }
    public function store(Request $request)
    {
        // authorize if you have a policy; fallback to simple check
        // $this->authorize('update', Stock::class);

        $validated = $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantity_to_discount' => 'sometimes|required|integer|min:1',
            'correct_quantity' => 'sometimes|required|integer|min:1',
            'type' => 'required|in:item_decrement,item_increment,stockTaking_decrement,stockTaking_increment',
            'reason' => 'nullable|string|max:255',
            'percentage' => 'nullable|string|max:255',
            'verified' => 'nullable|in:0,1',
            "source" => 'nullable',
            'custom_reason' => 'nullable|string|max:255',
            'order_id' => 'nullable|exists:orders,id',
            'note' => 'nullable|string',
            'employee_name' => 'nullable|string|max:255',
            'date_time' => 'nullable|date',
            'image' => 'nullable|image|max:20480',
        ]);
        $type = $validated['type'] ?? null;
        $qty = isset($validated['quantity_to_discount']) ? (int) $validated['quantity_to_discount'] : (int) $validated['correct_quantity'];
        if ($qty && $qty <= 0) {
            return response()->json(['error' => 'Invalid quantity'], 422);
        }
        $stock = Stock::findOrFail($validated['stock_id']);
        
        
        try {
            DB::transaction(function () use ($stock, $validated, $type, $qty) {
                // update stock quantity
                // if decrement ensure available
                //this handles item added from the manual item return and withdrawal form
                // for stock taking adjustments
                if (isset($validated['verified']) && $validated['verified'] == "1" && ($validated["type"] === 'stockTaking_decrement' || $validated["type"] === 'stockTaking_increment')) {
                    $avaiableQty = $stock->quantity ;
                    $availablePercentage = $stock->percentage ?? null;
                    $stock->update(['quantity' => $qty]);
                    $stock->update(['percentage' => $validated["percentage"] ?? null]);

                }
                if ($validated["type"] !== 'stockTaking_decrement' && $validated["type"] !== 'stockTaking_increment') {
                       $avaiableQty = $stock->quantity;
                    if ($type === 'item_decrement' && $stock->quantity < $qty) {
                        $stock->update(['quantity' => $qty]);
                        $stock->update(['percentage' => $validated["percentage"] ?? null]);
                    } elseif ($type === 'item_decrement' || $type === 'stockTaking_decrement') {
                        $stock->decrement('quantity', $qty);
                        $stock->update(['percentage' => $validated["percentage"] ?? null]);
                    } elseif ($type === 'item_increment' || $type === 'stockTaking_increment') {
                        $stock->increment('quantity', $qty);
                        $stock->update(['percentage' => $validated["percentage"] ?? null]);
                    }
                    $validated['verified'] = true;
                }
                // handle image upload if present
                $imagePath = null;
                if (request()->hasFile('image')) {
                    $file = request()->file('image');
                    $imagePath = $file->store('stock_adjustments', 'public');
                }
                StockAdjustment::create([
                    'stock_id' => $stock->id,
                    'quantity' => $qty,
                    'available_quantity_before' => $avaiableQty ?? null,
                    'available_percentage_before' => $availablePercentage ?? null,
                    'percentage' => $validated["percentage"] ?? null,
                    'type' => $type,
                    'source' => $validated["source"] ?? null,
                    'reason' => $validated['reason'] ?? null,
                    'verified' => isset($validated['verified']) ? $validated['verified'] : false,
                    'custom_reason' => $validated['custom_reason'] ?? null,
                    'order_id' => $validated['order_id'] ?? null,
                    'note' => $validated['note'] ?? null,
                    'employee_name' => $validated['employee_name'] ?? null,
                    'date_time' => $validated['date_time'] ?? null,
                    'image' => $imagePath,
                ]);

            });

            if ($request->ajax()) {
                 return response()->json(['success' => true, 'message' => __('dashboard.stock_updated_successfully')], 200);
            }

            return redirect()->back()->with('success', __('dashboard.stock_updated_successfully'));
        } catch (\Exception $e) {
            \Log::error('Stock adjustment creation failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, StockAdjustment $adjustment)
    {
        $validated = $request->validate([
            'quantity_to_discount' => 'sometimes|required|integer|min:1',
            'correct_quantity' => 'sometimes|required|integer|min:1',
            'type' => 'required|in:item_decrement,item_increment,stockTaking_decrement,stockTaking_increment',
            'reason' => 'nullable|string|max:255',
            'verified' => 'nullable|in:0,1',
            'percentage' => 'nullable|string|max:255',
            'custom_reason' => 'nullable|string|max:255',
            'order_id' => 'nullable|exists:orders,id',
            'note' => 'nullable|string',
            'employee_name' => 'nullable|string|max:255',
            'date_time' => 'nullable|date',
            'image' => 'nullable|image|max:20480',
        ]);

        $newQty = isset($validated['quantity_to_discount']) ? (int) $validated['quantity_to_discount'] : (int) $validated['correct_quantity'];
        if ($newQty && $newQty <= 0) {
            return response()->json(['error' => 'Invalid quantity'], 422);
        }
        $newType = $validated['type'] ?? $adjustment->type;

        try {
            DB::transaction(function () use ($adjustment, $validated, $newQty, $newType) {
                $stock = $adjustment->stock;
                if (!$stock) {
                    throw new \Exception('Related stock not found');
                }

                // 1. Revert previous adjustment
                if ($adjustment->type === 'item_decrement' ||( $adjustment->type === 'stockTaking_decrement' && isset($validated['verified']) && $validated['verified'] == "1" )) {
                    $stock->increment('quantity', $adjustment->quantity);
                } elseif ($adjustment->type === 'item_increment' || ( $adjustment->type === 'stockTaking_increment' && isset($validated['verified']) && $validated['verified'] == "1" )) {
                    $stock->decrement('quantity', $adjustment->quantity);
                }

                // 2. Apply new adjustment
                // this handles item added from the manual item return and withdrawal form
                if ($newType === 'item_decrement') {
                    if ($stock->quantity < $newQty) {
                        $stock->update(['quantity' => $newQty]);
                    } 
                    else {
                        $stock->decrement('quantity', $newQty);
                    }
                } 
                elseif ($newType === 'item_increment') {
                    $stock->increment('quantity', $newQty);
                }
                // for stock taking adjustments
                if (($newType === 'stockTaking_decrement' || $newType === 'stockTaking_increment')) {
                    $availableQtyBefore = $stock->quantity ?? 0;
                    $availablePercentageBefore = $stock->percentage ?? null;
                    $validated['verified'] = false;
                } 
                // image handling
                if (request()->hasFile('image')) {
                    $file = request()->file('image');
                    $imagePath = $file->store('stock_adjustments', 'public');
                } else {
                    $imagePath = $adjustment->image;
                }
                $adjustment->update([
                    'quantity' => $newQty,
                    'available_quantity_before' => $availableQtyBefore ?? null,
                    'available_percentage_before' => $availablePercentageBefore ?? null,
                    'percentage' => $validated["percentage"] ?? null,
                    'type' => $newType, 
                    'reason' => $validated['reason'] ?? $adjustment->reason,
                    'custom_reason' => $validated['custom_reason'] ?? $adjustment->custom_reason,
                    'note' => $validated['note'] ?? $adjustment->note,
                    'employee_name' => $validated['employee_name'] ?? $adjustment->employee_name,
                    'date_time' => $validated['date_time'] ?? $adjustment->date_time,
                    "order_id" => $validated["order_id"] ,
                    'image' => $imagePath,
                ]);
            });

            return response()->json(['message' => __('dashboard.stock_updated_successfully')], 200);
        } catch (\Exception $e) {
            \Log::error('Stock adjustment update failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(StockAdjustment $adjustment)
    {
        try {
            DB::transaction(function () use ($adjustment) {
                $stock = $adjustment->stock;
                if ($stock) {
                    // revert the adjustment
                    if ($adjustment->type === 'item_decrement') {
                        $stock->increment('quantity', $adjustment->quantity);
                    } else {
                        // was increment
                        $stock->decrement('quantity', $adjustment->quantity);
                    }
                }

                $adjustment->delete();
            });

            return response()->json(['message' => __('dashboard.success')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Download the image associated with a stock adjustment.
     */
    public function downloadImage($id)
    {
        $adjustment = StockAdjustment::findOrFail($id);
        if (!$adjustment->image) {
            return response()->json(['error' => 'No image found for this adjustment.'], 404);
        }
      
        return \Storage::disk('public')->download($adjustment->image, basename($adjustment->image));
    }
    /**
     * Return adjustment data as JSON for edit modal.
     */
    public function json(StockAdjustment $adjustment)
    {
        $adjustment->load('stock');
        return response()->json($adjustment);
    }
    public function stockTakingCreate()
    {
        $stocks = \App\Models\Stock::all();
        $stockTakingItems = StockAdjustment::where('source', 'stockTaking')->paginate(10);
        return view('dashboard.stocks.stockTaking', compact('stockTakingItems', 'stocks'));
    }

        /**
         * Display a listing of stock taking adjustments.
         */
        public function stockTakingIndex()
        {
            $query = StockAdjustment::where('source', 'stockTaking')->where('verified', true);

            $dateFrom = request('date_from');
            $dateTo = request('date_to');
            if ($dateFrom) {
                $query->whereDate('date_time', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('date_time', '<=', $dateTo);
            }

            $stockTakingReport = $query->paginate(10);
            return view('dashboard.stocks.stockTaking', compact('stockTakingReport'));
        }
}
