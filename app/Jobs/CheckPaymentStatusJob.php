<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\PaymentLink;
use App\Services\PaymenntService;
use Illuminate\Support\Facades\Log;

class CheckPaymentStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes timeout
    public $tries = 3; // Retry 3 times if failed

    protected $paymentLinkId;
    protected $forceCheck;

    /**
     * Create a new job instance.
     */
    public function __construct($paymentLinkId = null, $forceCheck = false)
    {
        $this->paymentLinkId = $paymentLinkId;
        $this->forceCheck = $forceCheck;
    }

    /**
     * Execute the job.
     */
    public function handle(PaymenntService $paymenntService)
    {
        try {
            if ($this->paymentLinkId) {
                // Check specific payment link
                $this->checkSinglePayment($paymenntService);
            } else {
                // Check all pending payments
                $this->checkAllPayments($paymenntService);
            }
        } catch (\Exception $e) {
            Log::error('CheckPaymentStatusJob failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Check a single payment link
     */
    protected function checkSinglePayment(PaymenntService $paymenntService)
    {
        $paymentLink = PaymentLink::find($this->paymentLinkId);

        if (!$paymentLink) {
            Log::warning('Payment link not found', ['id' => $this->paymentLinkId]);
            return;
        }

        $this->updatePaymentStatus($paymentLink, $paymenntService);
    }

    /**
     * Check all pending payments
     */
    protected function checkAllPayments(PaymenntService $paymenntService)
    {
        $query = PaymentLink::where('status', 'pending');

        if (!$this->forceCheck) {
            $query->needsStatusCheck(5); // Only check payments not checked in last 5 minutes
        }

        $paymentLinks = $query->get();

        $locale = app()->getLocale();

        if ($locale === 'ar') {
            Log::info("فحص {$paymentLinks->count()} رابط دفع");
        } else {
            Log::info("Checking {$paymentLinks->count()} payment links");
        }

        foreach ($paymentLinks as $paymentLink) {
            try {
                $this->updatePaymentStatus($paymentLink, $paymenntService);

                // Small delay to avoid overwhelming the API
                usleep(500000); // 0.5 seconds

            } catch (\Exception $e) {
                Log::error('Failed to check payment link', [
                    'payment_link_id' => $paymentLink->id,
                    'error' => $e->getMessage()
                ]);

                // Continue with next payment link
                continue;
            }
        }
    }

    /**
     * Update payment status
     */
    protected function updatePaymentStatus(PaymentLink $paymentLink, PaymenntService $paymenntService)
    {
        $result = $paymenntService->getCheckoutStatus($paymentLink->checkout_id);
        $locale = app()->getLocale();

        if ($result['success']) {
            $status = $result['data']['status'] ?? 'pending';
            $paidAt = null;

            if ($status === 'PAID') {
                $status = 'paid';
                $paidAt = now();

                if ($locale === 'ar') {
                    Log::info("تم دفع المدفوعة {$paymentLink->id}", [
                        'order_id' => $paymentLink->order_id,
                        'amount' => $paymentLink->amount
                    ]);
                } else {
                    Log::info("Payment {$paymentLink->id} is now PAID", [
                        'order_id' => $paymentLink->order_id,
                        'amount' => $paymentLink->amount
                    ]);
                }

                // Here you can add additional logic like:
                // - Update order status
                // - Send notifications
                // - Create invoices

            } elseif ($status === 'CANCELLED') {
                $status = 'cancelled';
                if ($locale === 'ar') {
                    Log::info("تم إلغاء المدفوعة {$paymentLink->id}");
                } else {
                    Log::info("Payment {$paymentLink->id} was CANCELLED");
                }
            } elseif ($status === 'EXPIRED') {
                $status = 'expired';
                if ($locale === 'ar') {
                    Log::info("انتهت صلاحية المدفوعة {$paymentLink->id}");
                } else {
                    Log::info("Payment {$paymentLink->id} has EXPIRED");
                }
            }

            $paymentLink->update([
                'status' => $status,
                'paid_at' => $paidAt,
                'last_status_check' => now()
            ]);
        } else {
            if ($locale === 'ar') {
                Log::warning('فشل في الحصول على حالة المدفوعة', [
                    'payment_link_id' => $paymentLink->id,
                    'error' => $result['error'] ?? 'خطأ غير معروف'
                ]);
            } else {
                Log::warning('Failed to get payment status', [
                    'payment_link_id' => $paymentLink->id,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
            }

            // Update last check time even if failed
            $paymentLink->update(['last_status_check' => now()]);
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception)
    {
        Log::error('CheckPaymentStatusJob failed permanently', [
            'payment_link_id' => $this->paymentLinkId,
            'error' => $exception->getMessage()
        ]);
    }
}
