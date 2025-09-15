<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use App\Http\Requests\StockAdjustmentRequest;
use App\Http\Requests\StockAdjustmentUpdateRequest;
use Illuminate\Support\Facades\DB;
use App\Services\StockAdjustmentService;

class StockAdjustmentController extends Controller
{
    protected $stockAdjustmentService;

    public function __construct(StockAdjustmentService $stockAdjustmentService)
    {
        $this->stockAdjustmentService = $stockAdjustmentService;
    }

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
    public function store(StockAdjustmentRequest $request)
    {
        // authorize if you have a policy; fallback to simple check
        // $this->authorize('update', Stock::class);

        $type = $request['type'] ?? null;
        $qty = isset($request['quantity_to_discount']) ? (int) $request['quantity_to_discount'] : (int) $request['correct_quantity'];
        if ($qty && $qty <= 0) {
            return response()->json(['error' => 'Invalid quantity'], 422);
        }
        try {
            $adjustment = $this->stockAdjustmentService->createAdjustment($request->all());
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => __('dashboard.stock_updated_successfully')], 200);
            }
            return redirect()->back()->with('success', __('dashboard.stock_updated_successfully'));
        } catch (\Exception $e) {
            \Log::error('Stock adjustment creation failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(StockAdjustmentUpdateRequest $request, StockAdjustment $adjustment)
    {
        $newQty = isset($request['quantity_to_discount']) ? (int) $request['quantity_to_discount'] : (int) $request['correct_quantity'];
        if ($newQty && $newQty <= 0) {
            return response()->json(['error' => 'Invalid quantity'], 422);
        }
        try {
            $this->stockAdjustmentService->updateAdjustment($adjustment, $request->all());
            return response()->json(['message' => __('dashboard.stock_updated_successfully')], 200);
        } catch (\Exception $e) {
            \Log::error('Stock adjustment update failed: ' . $e->getMessage());
            \Log::error('Request data: ' . $e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(StockAdjustment $adjustment)
    {
        try {
            $this->stockAdjustmentService->deleteAdjustment($adjustment);
            return response()->json(['message' => __('dashboard.success')], 200);
        } catch (\Exception $e) {
            \Log::error('Stock adjustment deletion failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
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
        $stockTakingItems = StockAdjustment::where('source', 'stockTaking')->paginate(10) ?? "no-data";
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

            $stockTakingReport = $query->paginate(10) ;
            return view('dashboard.stocks.stockTaking', compact('stockTakingReport'));
        }
}
