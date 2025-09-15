<?php
namespace App\Services;

use App\Models\Stock;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentService
{
    public function createAdjustment(array $validated)
    {
        $type = $validated['type'] ?? null;
        $qty = isset($validated['quantity_to_discount']) ? (int) $validated['quantity_to_discount'] : (int) $validated['correct_quantity'];
        $stock = Stock::findOrFail($validated['stock_id']);
        $result = null;
        DB::transaction(function () use ($stock, $validated, $type, $qty, &$result) {
            $availableQtyBefore = $stock->quantity;
            $previous_percentage = $stock->percentage ?? null;
            $availableQtyAfter = null;
            if ($type === 'stockTaking_decrement' || $type === 'stockTaking_increment') {
                $availableQtyAfter = $this->handleStockTakingAdjustment($stock, $validated, $type, $qty);
            } else {
                $availableQtyAfter = $this->handleItemAdjustment($stock, $validated, $type, $qty);
                $validated['verified'] = true;
            }
            // Image upload
            $imagePath = null;
            if (request()->hasFile('image')) {
                $file = request()->file('image');
                $imagePath = $file->store('stock_adjustments', 'public');
            }
            $result = StockAdjustment::create([
                'stock_id' => $stock->id,
                'available_quantity_before' => $availableQtyBefore ?? null,
                'available_quantity_after' => $availableQtyAfter ?? null,
                'available_percentage_before' => $previous_percentage ?? null,
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
        return $result;
    }

    private function handleStockTakingAdjustment($stock, $validated, $type, $qty)
    {
        if (isset($validated['verified']) && $validated['verified'] == "1") {
            $stock->update(['quantity' => $qty]);
            $stock->update(['percentage' => $validated["percentage"] ?? null]);
        }
        return $qty;
    }

    private function handleItemAdjustment($stock, $validated, $type, $qty)
    {
        if ($type === 'item_decrement' && $stock->quantity < $qty) {
            $stock->update(['quantity' => $qty]);
            $stock->update(['percentage' => $validated["percentage"] ?? null]);
            return $qty;
        } elseif ($type === 'item_decrement') {
            $stock->decrement('quantity', $qty);
            $stock->update(['percentage' => $validated["percentage"] ?? null]);
            return $stock->quantity;
        } elseif ($type === 'item_increment') {
            $stock->increment('quantity', $qty);
            $stock->update(['percentage' => $validated["percentage"] ?? null]);
            return $stock->quantity;
        }
        return $stock->quantity;
    }

    public function updateAdjustment(StockAdjustment $adjustment, array $validated)
    {
        $newQty = isset($validated['quantity_to_discount']) ? (int) $validated['quantity_to_discount'] : (int) $validated['correct_quantity'];
        $newType = $validated['type'] ?? $adjustment->type;
        DB::transaction(function () use ($adjustment, $validated, $newQty, $newType) {
            $stock = $adjustment->stock;
            if (!$stock) {
                throw new \Exception('Related stock not found');
            }
            // Revert previous adjustment
            if ($adjustment->type === 'stockTaking_decrement' || $adjustment->type === 'stockTaking_increment') {
                $this->revertStockTakingAdjustment($stock, $adjustment);
            } else {
                $this->revertItemAdjustment($stock, $adjustment);
            }
            // Apply new adjustment
            if ($newType === 'stockTaking_decrement' || $newType === 'stockTaking_increment') {
                $validated['verified'] = false;
                $this->handleStockTakingAdjustment($stock, $validated, $newType, $newQty);
            } else {
                $this->handleItemAdjustment($stock, $validated, $newType, $newQty);
            }
            // Image handling
            $imagePath = request()->hasFile('image') ? request()->file('image')->store('stock_adjustments', 'public') : $adjustment->image;
            $adjustment->update([
                'available_quantity_after' => $newQty,
                'percentage' => $validated["percentage"] ?? null,
                'verified' => isset($validated['verified']) ? $validated['verified'] : $adjustment->verified,
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
        return $adjustment;
    }

    private function revertStockTakingAdjustment($stock, $adjustment)
    {
        if (isset($adjustment->verified) && $adjustment->verified == "1") {
            $stock->update(['quantity' => $adjustment->available_quantity_before]);
        }
    }

    private function revertItemAdjustment($stock, $adjustment)
    {
        if ($adjustment->type === 'item_decrement') {
            $stock->update(['quantity' => $adjustment->available_quantity_before]);
        } elseif ($adjustment->type === 'item_increment') {
            $stock->update(['quantity' => $adjustment->available_quantity_before]);
        }
    }

    public function deleteAdjustment(StockAdjustment $adjustment)
    {
        DB::transaction(function () use ($adjustment) {
            $stock = $adjustment->stock;
            if ($stock) {
                if ($adjustment->type === 'stockTaking_decrement' || $adjustment->type === 'stockTaking_increment') {
                    $this->revertStockTakingAdjustment($stock, $adjustment);
                } else {
                    $this->revertItemAdjustment($stock, $adjustment);
                }
            }
            $adjustment->delete();
        });
        return true;
    }
}
