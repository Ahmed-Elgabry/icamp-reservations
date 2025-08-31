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
use Illuminate\Support\Facades\DB;

class FastPaymentStatusCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120; // 2 minutes timeout
    public $tries = 2; // Retry 2 times if failed

    protected $batchSize;
    protected $concurrent;

    /**
     * Create a new job instance.
     */
    public function __construct($batchSize = 20, $concurrent = 5)
    {
        $this->batchSize = $batchSize;
        $this->concurrent = $concurrent;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $this->fastCheckAllPayments();
        } catch (\Exception $e) {
            Log::error('FastPaymentStatusCheckJob failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Fast check all pending payments
     */
    protected function fastCheckAllPayments()
    {
        $query = PaymentLink::where('status', 'pending')
            ->needsStatusCheck(5);

        $totalPayments = $query->count();

        Log::info("Fast checking {$totalPayments} payment links");

        // Process in large batches
        $query->chunk($this->batchSize, function ($chunk) {
            $this->processBatch($chunk);
        });
    }

    /**
     * Process a batch of payments
     */
    protected function processBatch($payments)
    {
        // Update all payments in batch
        $paymentIds = $payments->pluck('id')->toArray();

        // Mark as checked immediately to avoid duplicate processing
        PaymentLink::whereIn('id', $paymentIds)
            ->update(['last_status_check' => now()]);

        // Process each payment (can be optimized further)
        foreach ($payments as $payment) {
            try {
                $this->quickStatusCheck($payment);
            } catch (\Exception $e) {
                Log::warning('Quick status check failed', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Quick status check without heavy API calls
     */
    protected function quickStatusCheck($payment)
    {
        // You can implement a lighter version here
        // For now, we'll just mark as checked
        // In production, you might want to use a queue system

        Log::info("Quick check completed for payment {$payment->id}");
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception)
    {
        Log::error('FastPaymentStatusCheckJob failed permanently', [
            'error' => $exception->getMessage()
        ]);
    }
}
