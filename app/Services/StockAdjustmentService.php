<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockAdjustment;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Log;

class StockAdjustmentService
{
    /**
     * Adjust stock and create StockAdjustment record inside a DB transaction.
     * Expects keys: id, stockId, qty, status, orderId
     * Returns array with remaining, decrement, stock_id, pivot_id.
     */
    public function decrmentOrIncrementStock(array $data): array
    {
        return FacadesDB::transaction(function () use ($data) {
            $affected = FacadesDB::table('service_stock')
                ->where('id', $data['id'])
                ->where('stock_id', $data['stockId'])
                ->update([
                    'required_qty' => $data['qty'],
                    'updated_at'   => now(),
                    'latest_activity' => $data['status']
                ]);

            abort_if($affected === 0, 404, 'Pivot not found for this stock.');

            $stock = Stock::findOrFail($data['stockId']);

            $available_quantity_before = $stock->quantity;
            $available_percentage_before = $stock->percentage ?? null;

            $status = $data['status'] === 'increment' ? 'increment' : 'decrement';
            $availableQtyAfter = $this->computeAfterQuantity($stock, $status, $data['qty']);

            $available_percentage_after = $this->computeAfterPercentage($stock, $status, $data['qty']);

            // create snapshot record before mutating
            StockAdjustment::create([
                'available_quantity_before' => $available_quantity_before,
                'available_quantity_after' => $availableQtyAfter,
                'stock_id' => $data['stockId'],
                'quantity' => $data['qty'],
                "reason" => "for_orders",
                // type  is increment to order and decremnt to stock and vice versa
                'type' => $data['status'] === 'increment' ? 'item_increment' : 'item_decrement',
                'order_id' => $data['orderId'] ?? null,
                'source' => 'Reservation',
                'percentage' => $available_percentage_after,
                'available_percentage_before' => $available_percentage_before,
                'verified' => 1,
                'date_time' => now(),
                'employee_name' => auth()->user()->name ?? null,
            ]);

            // lock and apply change
            $locked = Stock::whereKey($data['stockId'])->lockForUpdate()->firstOrFail();
            $this->applyChange($locked, $data['status'], $data['qty']);
            $locked->refresh();

            return [
                'remaining' => (int) $locked->quantity,
                'decrement' => (int) $data['qty'],
                'stock_id'  => (int) $locked->id,
                'pivot_id'  => (int) $data['id'],
            ];
        });
    }

    protected function computeAfterQuantity(Stock $stock, string $status, $qty = null)
    {
        if ($stock->quantity === null) {
            return null;
        }
        if ($status === 'increment') {
            return $stock->quantity + $qty;
        }else if ($status === 'decrement') {
            return $stock->quantity - $qty;
        }elseif($status === 'reset') {
            return $qty;
        }
    }

    protected function computeAfterPercentage(Stock $stock, string $status, $qty = null)
    {
        if ($stock->percentage === null) {
            return null;
        }
        if ($status === 'increment') {
            return $stock->percentage + $qty;
        }else if ($status === 'decrement') {
            return $stock->percentage - $qty;
        }elseif($status === 'reset') {
            return $qty;
        }

    }

    protected function applyChange(Stock $stock, string $status, float $qty): void
    {
        switch ($status) {
            case 'increment':
                if ($stock->percentage) {
                    $stock->increment('percentage', $qty);
                } else {
                    $stock->increment('quantity', $qty);
                }
                break;

            case 'decrement':
                if (!$this->checkForSufficientStock($stock->id, $qty, $status)) {
                    abort(422, __('dashboard.insufficient_stock'));
                }
                if ($stock->percentage) {
                    $stock->decrement('percentage', $qty);
                } else {
                    $stock->decrement('quantity', $qty);
                }
                break;

            default:
                abort(422, __('dashboard.not_found'));
        }
    }
    public function checkForSufficientStock(int $stockId, int $qty, string $status): bool
    {
        $stock = Stock::find($stockId);
        if (!$stock) {
            return false;
        }

        if ($status === 'decrement') {
            if ($stock->percentage !== null) {
                return $stock->percentage >= $qty;
            }
            return $stock->quantity >= $qty;
        }
        // For increment, always true
        return true;
    }

    /**
     * Create a general stock adjustment (used by StockAdjustmentController::store)
     */
    public function createAdjustment(array $validated)
    {
        $type = $validated['type'] ?? null;
        if (isset($validated['quantity_to_discount'])) {
            $qty = (int) $validated['quantity_to_discount'];
        } else if (isset($validated['correct_quantity'])) {
            $qty = (int) ($validated['correct_quantity'] ?? 0);
        } else {
            $qty = null;
        }   
        $stock = Stock::findOrFail($validated['stock_id']);
        $result = null;
        FacadesDB::transaction(function () use ($stock, $validated, $type, $qty, &$result) {
            $availableQtyBefore = $stock->quantity;
            $previous_percentage = $stock->percentage ?? null;
            $availableQtyAfter = null;
            $available_percentage_after = null;
            if ($type === 'stockTaking_decrement' || $type === 'stockTaking_increment') {
                $available_percentage_after = $this->computeAfterPercentage($stock,  'reset' , $validated['percentage'] ?? 0);
                $availableQtyAfter = $this->computeAfterQuantity($stock,  'reset' , $qty);
                $this->handleStockTakingAdjustment($stock, $validated, $type, $availableQtyAfter , $available_percentage_after);
            } else {
                $availableQtyAfter = $this->computeAfterQuantity($stock, $type === 'item_increment' ? 'increment' : 'decrement', $qty);
                $available_percentage_after = $this->computeAfterPercentage($stock, $type === 'item_increment' ? 'increment' : 'decrement', $validated['percentage'] ?? 0   );
                $this->handleItemAdjustment($stock, $validated, $type, $availableQtyAfter , $available_percentage_after);
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
                'percentage' => $available_percentage_after ?? null,
                'type' => $type,
                'source' => $validated["source"] ?? null,
                'reason' => $validated['reason'] ?? null,
                'verified' => isset($validated['verified']) ? $validated['verified'] : false,
                'custom_reason' => $validated['custom_reason'] ?? null,
                'order_id' => $validated['order_id'] ?? null,
                'note' => $validated['note'] ?? null,
                'employee_name' => auth()->user()->name ?? null,
                'date_time' => $validated['date_time'] ?? now(),
                'image' => $imagePath,
            ]);
        });

        return $result;
    }

    private function handleStockTakingAdjustment($stock, $validated, $type, $availableQtyAfter = null, $available_percentage_after = null , $adjustment = null)
    {
        if (isset($validated["verified"]) && $validated["verified"] == true) {
            if ($stock->percentage !== null) {
                $stock->update(['percentage' => $available_percentage_after ]);
            } else {
                $stock->update(['quantity' => $availableQtyAfter]);
            }
        }
        return $available_percentage_after ?? $availableQtyAfter;
    }

    private function handleItemAdjustment($stock, $validated, $type, $availableQtyAfter = null, $available_percentage_after = null)
    {
        // operate on percentage when percentage tracking is enabled
        if ($stock->percentage !== null) {
           $stock->update(['percentage' => $available_percentage_after]);
            return $stock->percentage;
        }else{
            $stock->update(['quantity' => $availableQtyAfter]);
            return $stock->quantity;
        }
    }

    public function updateAdjustment(StockAdjustment $adjustment, array $validated)
    {
        $newQty = isset($validated['quantity_to_discount']) ? (int) $validated['quantity_to_discount'] : (int) ($validated['correct_quantity'] ?? 0);
        $newType = $validated['type'] ?? $adjustment->type;

        FacadesDB::transaction(function () use ($adjustment, $validated, $newQty, $newType) {
            $stock = $adjustment->stock;
            if (!$stock) {
                throw new \Exception('Related stock not found');
            }

            // Revert previous adjustment
            if ($adjustment->type === 'stockTaking_decrement' || $adjustment->type === 'stockTaking_increment') {
                $returned = $this->revertStockTakingAdjustment($stock, $adjustment);
                extract($returned);
            } else {
                $returned = $this->revertItemAdjustment($stock, $adjustment);
                extract($returned);
            }

            // Apply new adjustment
            if ($newType === 'stockTaking_decrement' || $newType === 'stockTaking_increment') {
                $validated['verified'] = false;
                $available_percentage_after = $this->computeAfterPercentage($stock, 'reset', isset($validated['percentage']) ? $validated['percentage'] : null);
                $available_quantity_after = $this->computeAfterQuantity($stock, 'reset', $newQty);
                $this->handleStockTakingAdjustment($stock, $validated, $newType, $newQty , $available_percentage_after , $adjustment);
            } else {
                $available_quantity_after = $this->computeAfterQuantity($stock, $newType === 'item_increment' ? 'increment' : 'decrement', $newQty);
                $available_percentage_after = $this->computeAfterPercentage($stock, $newType === 'item_increment' ? 'increment' : 'decrement', isset($validated['percentage']) ? $validated['percentage'] : null);
                $this->handleItemAdjustment($stock, $validated, $newType, $available_quantity_after, $available_percentage_after);
            }

            // Image handling
            $imagePath = request()->hasFile('image') ? request()->file('image')->store('stock_adjustments', 'public') : $adjustment->image;
            // prefer the computed available percentage after (from this transaction) when present
            $final_percentage = $available_percentage_after ?? $stock->percentage ?? $adjustment->percentage ?? null;

            $adjustment->update([
                'available_quantity_after' => $available_quantity_after ?? $stock->quantity,
                'available_quantity_before' => $available_quantity_before ?? $adjustment->available_quantity_before,
                'percentage' =>  $final_percentage,
                'verified' => isset($validated['verified']) ? $validated['verified'] : $adjustment->verified,
                'type' => $newType,
                'reason' => $validated['reason'] ?? $adjustment->reason,
                'custom_reason' => $validated['custom_reason'] ?? $adjustment->custom_reason,
                'note' => $validated['note'] ?? $adjustment->note,
                'employee_name' => auth()->user()->name ?? $adjustment->employee_name,
                'date_time' => $validated['date_time'] ?? $adjustment->date_time,
                'order_id' => $validated['order_id'] ?? $adjustment->order_id,
                'image' => $imagePath,
            ]);
        });

        return $adjustment;
    }

    private function revertStockTakingAdjustment($stock, $adjustment)
    {
        if (isset($adjustment->verified) && $adjustment->verified == "1") {
            if ($stock->percentage !== null) {
                //revert only if the updated stock adjustment is the last one because stock taking not decrement / increment it just set the stock to a specific value
                if ($stock->percentage === $adjustment->percentage) {
                    $stock->update(['percentage' => $adjustment->available_percentage_before]);
                    return ["available_percentage_before" => $adjustment->available_percentage_before];
                }
            } else {
                 //revert only if the updated stock adjustment is the last one because stock taking not decrement / increment it just set the stock to a specific value

                if ($stock->quantity === $adjustment->available_quantity_after) {
                    $stock->update(['quantity' => $adjustment->available_quantity_before]);
                    return ["available_quantity_before" => $adjustment->available_quantity_before];
                }
            }
        } else {
            return [];
        }
    }

    private function revertItemAdjustment($stock, $adjustment)
    {
        // Revert to the stored snapshot. Prefer percentage if present.
        if ( $stock->percentage !== null) {
                $diff = abs(($adjustment->available_percentage_before ?? 0) - ($adjustment->percentage ?? 0));
                if ($adjustment->type === 'item_increment') {
                    $stock->decrement('percentage', $diff);
                } else {
                    $stock->increment('percentage', $diff);
                }
                return ["available_percentage_before" => $adjustment->available_percentage_before];
        } else {
            $diff = abs(($adjustment->available_quantity_before ?? 0) - ($adjustment->quantity ?? 0));
            if ($adjustment->type === 'item_increment') {
                $stock->decrement('quantity', $diff);
            } else {
                $stock->increment('quantity', $diff);
            }
            return ["available_quantity_before" => $adjustment->available_quantity_before];
        }
    }

    public function deleteAdjustment(StockAdjustment $adjustment)
    {
        FacadesDB::transaction(function () use ($adjustment) {
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
