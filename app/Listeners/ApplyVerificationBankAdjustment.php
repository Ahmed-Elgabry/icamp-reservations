<?php

namespace App\Listeners;

use App\Events\VerificationStatusChanged;
use Illuminate\Support\Facades\DB;

class ApplyVerificationBankAdjustment 
{
    /**
     * Handle the verification status changed event.
     */
    public function handle(VerificationStatusChanged $event): void
    {
        $item = $event->item;
        $action = $event->action; // 'addon' | 'payment' | 'expense' | 'warehouse_sale'
        // Use the verification state from the event, not from the item
        $verified = $event->verified;

        // Expect models to expose: account_id, total/amount/price
        try {
            $accountId = $item->account_id ?? null;
            if (!$accountId) return; // Nothing to adjust

            $amount = $this->resolveAmount($action, $item);
            if ($amount <= 0) return;

            DB::beginTransaction();

            $account = \App\Models\BankAccount::find($accountId);
            if (!$account) {
                DB::rollBack();
                return;
            }

            if ($action === 'expense') {
                if ($verified) {
                    $account->decrement('balance', $amount);
                }else {
                    $account->increment('balance', $amount);
                }
            } else {
                if ($verified) {
                    $account->increment('balance', $amount);
                }else {
                    $account->decrement('balance', $amount);
                }
            }
            if ($action === 'warehouse_sales' && $verified) {
                $item->stock->decrement('quantity', $item->quantity);
            } elseif ($action === 'warehouse_sales' && !$verified) {
                $item->stock->increment('quantity', $item->quantity);
            }

            DB::commit();
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            \Log::error('ApplyVerificationBankAdjustment failed: '.$e->getMessage(), [
                'action' => $action,
                'item' => method_exists($item, 'getKey') ? $item->getKey() : null,
            ]);
            throw $e;
        }
    }

    private function resolveAmount(string $action, $item): float
    {
        // Unify amount resolution across models
        switch ($action) {
            case 'payment':
                return (float) ($item->price ?? 0);
            case 'expense':
                return (float) ($item->price ?? 0);
            case 'warehouse_sale':
                return (float) ($item->total_price ?? 0);
            case 'addon':
                return (float) ($item->price ?? 0);
            default:
                return 0.0;
        }
    }

    private function resolveVerified($item): bool
    {
        if (isset($item->verified)) {
            return (bool) $item->verified;
        }

        return false;
    }
}
