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
            'quantity_to_discount' => 'required|integer|min:1',
            'type' => 'required|in:item_decrement,item_increment',
            'reason' => 'nullable|string|max:255',
            'custom_reason' => 'nullable|string|max:255',
            'order_id' => 'nullable|exists:orders,id',
            'note' => 'nullable|string',
            'employee_name' => 'nullable|string|max:255',
            'date_time' => 'nullable|date',
            'image' => 'nullable|image|max:20480',
        ]);

        $type = $validated['type'] ?? null;
        $qty = (int) $validated['quantity_to_discount'];

        $stock = Stock::findOrFail($validated['stock_id']);

        
        try {
            DB::transaction(function () use ($stock, $validated, $type, $qty) {
                // update stock quantity
                // if decrement ensure available
                if ($type === 'item_decrement' && $stock->quantity < $qty) {
                    $stock->update(['quantity' => $qty]);
                } 
                // elseif ($type === 'item_decrement') {
                //     $stock->decrement('quantity', $qty);
                // } elseif ($type === 'item_increment') {
                //     $stock->increment('quantity', $qty);
                // }

                // handle image upload if present
                $imagePath = null;
                if (request()->hasFile('image')) {
                    $file = request()->file('image');
                    $imagePath = $file->store('stock_adjustments', 'public');
                }
                StockAdjustment::create([
                    'stock_id' => $stock->id,
                    'quantity' => $qty,
                    'type' => $type,
                    'reason' => $validated['reason'] ?? null,
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, StockAdjustment $adjustment)
    {
        $validated = $request->validate([
            'quantity_to_discount' => 'required|integer|min:1',
            'type' => 'required|in:item_decrement,item_increment',
            'reason' => 'nullable|string|max:255',
            'custom_reason' => 'nullable|string|max:255',
            'order_id' => 'nullable|exists:orders,id',
            'note' => 'nullable|string',
            'employee_name' => 'nullable|string|max:255',
            'date_time' => 'nullable|date',
            'image' => 'nullable|image|max:20480',
        ]);

        $newQty = (int) $validated['quantity_to_discount'];
        $newType = $validated['type'] ?? $adjustment->type;

        try {
            DB::transaction(function () use ($adjustment, $validated, $newQty, $newType) {
                $stock = $adjustment->stock;
                if (!$stock) {
                    throw new \Exception('Related stock not found');
                }

                // 1. Revert previous adjustment
                if ($adjustment->type === 'item_decrement') {
                    $stock->increment('quantity', $adjustment->quantity);
                } else {
                    $stock->decrement('quantity', $adjustment->quantity);
                }

                // 2. Apply new adjustment
                if ($newType === 'item_decrement') {
                    if ($stock->quantity < $newQty) {
                        $stock->update(['quantity' => $newQty]);
                    } 
                    else {
                        $stock->decrement('quantity', $newQty);
                    }
                } 
                else {
                    $stock->increment('quantity', $newQty);
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
}
