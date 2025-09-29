<?php

namespace App\Services;

use App\Models\Order;
use App\Models\BankAccount;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InsuranceVerificationService
{
    public function processInsuranceStatus(Order $order)
    {
        if (!$order->is_insurance_verified) {
            return false;
        }

        // Reset previous insurance status if exists
        if ($order->getOriginal('insurance_status')) {
            $this->resetPreviousInsuranceStatus($order);
        }

        $insuranceStatus = $order->insurance_status;
        $insuranceAmount = $order->partial_confiscation_amount ?? 0;
        $originalInsuranceAmount = $order->verifiedInsuranceAmount();

        if ($originalInsuranceAmount <= 0) {
            $errorMessage = "No insurance amount to process for order: " . $order->id;
            Log::error($errorMessage);
            throw new \RuntimeException($errorMessage);
        }

        try {
            switch ($insuranceStatus) {
                case 'returned':
                    $this->processReturnedInsurance($order);
                    break;
                case 'confiscated_partial':
                    $this->processPartialConfiscation($order, $insuranceAmount, $originalInsuranceAmount);
                    break;
                case 'confiscated_full':
                    $this->processFullConfiscation($order);
                    break;
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Error processing insurance status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reset the previous insurance status and adjust bank balances accordingly
     */
    private function resetPreviousInsuranceStatus(Order $order)
    {
        $originalStatus = $order->getOriginal('insurance_status');
        
        if (empty($originalStatus)) {
            return;
        }

        $payments = $order->verifiedInsurance()->with(['transaction', 'bankAccount'])->get();
        
        if ($originalStatus === 'returned') {
            // No need to reset anything for returned status as it was already processed
            return;
        }

        DB::beginTransaction();
        try {
            foreach ($payments as $payment) {
                if ($originalStatus === 'confiscated_partial' || $originalStatus === 'confiscated_full') {
                    $this->resetConfiscatedAmount($payment, $order);
                }
                
                // Reset payment status
                $payment->update([
                    'insurance_status' => null,
                    'insurance_handled_by' => null
                ]);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error resetting insurance status: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reset the confiscated amount for a payment
     */
    private function resetConfiscatedAmount($payment, $order)
    {
        if ($payment->transaction) {
            $confiscatedAmount = $payment->transaction->amount;
            
            // Reset transaction amount to full price
            $this->updateTransaction($payment->transaction, $payment->price);
            
            // Adjust bank balance
            if ($payment->account_id) {
                $this->adjustBankBalance($payment->account_id, $confiscatedAmount, 'decrement');
            }
        }
    }

    private function processReturnedInsurance(Order $order)
    {
        foreach ($order->verifiedInsurance()->get() as $insurance) {
            $this->updateInsuranceStatus($insurance, 'returned');
            $insuranceAmount = $insurance->transaction->amount;
            if ($insurance->transaction) {
                $this->updateTransaction($insurance->transaction, 0);
            }

            if ($insurance->account_id) {
                $this->adjustBankBalance($insurance->account_id, $insuranceAmount, 'decrement');
            }
        }
    }

    private function processPartialConfiscation(Order $order, $insuranceAmount, $originalInsuranceAmount)
    {
        foreach ($order->verifiedInsurance()->get() as $insurance) {
            $this->updateInsuranceStatus($insurance, 'confiscated_partial');
            
            if ($insurance->transaction) {
                $confiscatedPortion = $this->calculateConfiscatedPortion(
                    $insurance->price, 
                    $originalInsuranceAmount, 
                    $insuranceAmount
                );
                
                $this->updateTransaction($insurance->transaction, $confiscatedPortion);
                
                if ($insurance->account_id) {
                    $this->adjustBankBalance($insurance->account_id, $confiscatedPortion, 'increment');
                }
            }
        }
    }

    private function processFullConfiscation(Order $order)
    {
        foreach ($order->verifiedInsurance()->get() as $insurance) {
            $this->updateInsuranceStatus($insurance, 'confiscated_full');
            
            if ($insurance->transaction) {
                $this->updateTransaction($insurance->transaction, $insurance->price);
                
                if ($insurance->account_id) {
                    $this->adjustBankBalance($insurance->account_id, $insurance->price, 'increment');
                }
            }
        }
    }
    
    /**
     * Update insurance status for a payment
     */
    private function updateInsuranceStatus($insurance, $status)
    {
        $insurance->update([
            'insurance_status' => $status,
            'insurance_handled_by' => auth()->id()
        ]);
    }
    
    /**
     * Update transaction amount
     */
    private function updateTransaction($transaction, $amount)
    {
        $transaction->update([
            'amount' => $amount,
            'insurance_handled_by' => auth()->id()
        ]);
    }
    
    /**
     * Adjust bank balance
     */
    private function adjustBankBalance($accountId, $amount, $operation = 'increment')
    {
        $bankAccount = BankAccount::find($accountId);
        if ($bankAccount) {
            $bankAccount->$operation('balance', $amount);
        }
    }
    
    /**
     * Calculate confiscated portion based on payment ratio
     */
    private function calculateConfiscatedPortion($paymentAmount, $totalAmount, $confiscatedAmount)
    {
        if ($totalAmount <= 0) {
            return 0;
        }
        return ($paymentAmount / $totalAmount) * $confiscatedAmount;
    }
}
