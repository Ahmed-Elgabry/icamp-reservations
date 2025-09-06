<?php

namespace App\Listeners;

use App\Events\VerificationStatusChanged;
use App\Models\BankAccount;
use Illuminate\Support\Facades\DB;

class ApplyVerificationBankAdjustment 
{
    /**
     * Handle the verification status changed event.
     */
    public function handle(VerificationStatusChanged $event): void
    {
        $item = $event->item;
        $action = $event->action; // 'addon' | 'payment' | 'expense' | 'warehouse_sale' | 'insurance'
        // Use the verification state from the event, not from the item
        $verified = $event->verified;
        \Log::info("ApplyVerificationBankAdjustment handling action: $action for item ID: ".(method_exists($item, 'getKey') ? $item->getKey() : 'unknown'));
        // Expect models to expose: account_id, total/amount/price
        try {
            $accountId = $item->account_id  ?? null;
            if (!$accountId && $action !== "insurance") return; 

            $amount = $this->resolveAmount($action, $item);
            if ($amount <= 0 && $action !== "insurance") return;


            $account = BankAccount::find($accountId);
            if (!$account && $action !== "insurance") {
                return;
            }
            \Log::info("ApplyVerificationBankAdjustment processing action: $action for item ID: ".(method_exists($item, 'getKey') ? $item->getKey() : 'unknown')." with amount: $amount on account ID: $accountId");
            if ($action === 'expense') {
                if ($verified) {
                    $account->decrement('balance', $amount);
                }else {
                    $account->increment('balance', $amount);
                }
            } elseif ($action == 'insurance') {
                $insurances = $item->payments()->where('statement', 'the_insurance')->get();
                $newstatus = $verified ? "0" : "1"; // Use the new status from event

                foreach ($insurances as $insurance) {
                    if (!$insurance->account_id) {
                        continue;
                    }
                    
                    $bankAccount = BankAccount::find($insurance->account_id);
                    if (!$bankAccount) {
                        continue;
                    }
                    
                    // Apply bank balance changes based on new verification status
                    if ($verified) {
                        if ($insurance->verified == "1") {
                            $bankAccount->increment('balance', $insurance->transaction->amount);
                        }
                    } else {

                        if ($insurance->verified == "1") {
                            $bankAccount->decrement('balance', $insurance->transaction->amount);
                        }
                    }
                    
                    // Update payment and transaction verification status
                    $insurance->update(['verified' => $newstatus]);
                    if ($insurance->transaction) {
                        $insurance->transaction->update(['verified' => $newstatus]);
                    }
                }
                
                $item->update(['insurance_approved' => $newstatus]);
                            DB::commit();
            } else {
                if ($verified) {
                    \Log::info("1");
                    $account->increment('balance', $amount);
                }else {
                    \Log::info("2");
                    \Log::info("Decrementing account ID: $accountId by amount: $amount");
                    $account->decrement('balance', $amount);
                }
            }
            if ($action === 'warehouse_sales' && $verified) {
                \Log::info("x");
                $item->stock()->decrement('quantity', $item->quantity);
                \Log::info($item->stock->quantity);
            } elseif ($action === 'warehouse_sales' && !$verified) {
                                \Log::info("y");

                $item->stock()->increment('quantity', $item->quantity);
                                \Log::info($item->stock->quantity);
            }
        } catch (\Exception $e) {
            \Log::error('ApplyVerificationBankAdjustment failed: '.$e->getMessage(), [
                'action' => $action,
                'item' => method_exists($item, 'getKey') ? $item->getKey() : null,
            ]);
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
            case 'general_revenue_deposit':
                return (float) ($item->price ?? 0);
            case 'warehouse_sales':
                return (float) ($item->total_price ?? 0);
            case 'addon':
                return (float) ($item->price ?? 0);
            case 'insurance':
                return (float) ($item->verifiedInsuranceAmount() ?? 0);
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
