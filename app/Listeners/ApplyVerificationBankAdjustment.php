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
        // Expect models to expose: account_id, total/amount/price
        try {
            $accountId = $item->account_id  ?? null;
            if (!$accountId && $action !== "insurance" && $action !== "stockTaking") return;

            $amount = $this->resolveAmount($action, $item);
            if ($amount <= 0 && $action !== "insurance" && $action !== "stockTaking") return;

            $account = BankAccount::find($accountId);
            if (!$account && $action !== "insurance" && $action !== "stockTaking") {
            return;
            }

            switch ($action) {
            case 'expense':
                if ($verified) {
                $account->decrement('balance', $amount);
                } else {
                $account->increment('balance', $amount);
                }
                break;
            case "stockTaking" :
                if ($verified) {
                    $item->update(['available_quantity_before' => $item->stock->quantity]);
                    $item->stock()->update(['quantity' => $item->quantity]);
                    $item->stock()->update(['percentage' => $item->percentage ?? null]);
                } else {
                    $item->stock()->update(['quantity' => $item->available_quantity_before]);
                    $item->stock()->update(['percentage' => null]);
                }
                break;
            case 'insurance':
                $insurances = $item->payments()->where('statement', 'the_insurance')->get();
                $newstatus = $verified ? "0" : "1";
                foreach ($insurances as $insurance) {
                if (!$insurance->account_id) continue;
                $bankAccount = BankAccount::find($insurance->account_id);
                if (!$bankAccount) continue;
                if ($verified) {
                    if ($insurance->verified == "1") {
                    $bankAccount->increment('balance', $insurance->transaction->amount);
                    }
                } else {
                    if ($insurance->verified == "1") {
                    $bankAccount->decrement('balance', $insurance->transaction->amount);
                    }
                }
                $insurance->update(['verified' => $newstatus]);
                if ($insurance->transaction) {
                    $insurance->transaction->update(['verified' => $newstatus]);
                }
                }
                $item->update(['insurance_approved' => $newstatus]);
                DB::commit();
                break;

            case 'warehouse_sales':
                if ($verified) {
                    if ($item->stock->quantity < $item->quantity) {
                        $item->verified  = false ; 
                        $item->transaction = false ;
                        throw new \Exception(__('dashboard.insufficient_stock'));
                    }
                   $item->stock()->decrement('quantity', $item->quantity);
                } else {
                   $item->stock()->increment('quantity', $item->quantity);
                }
                if ($verified) {
                $account->increment('balance', $amount);
                } else {
                $account->decrement('balance', $amount);
                }
                break;

            case 'payment':
            case 'addon':
            case 'general_revenue_deposit':
                if ($verified) {
                $account->increment('balance', $amount);
                } else {
                $account->decrement('balance', $amount);
                }
                break;
            }
        } catch (\Exception $e) {
            // Handle exception or log error
            DB::rollBack();
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
