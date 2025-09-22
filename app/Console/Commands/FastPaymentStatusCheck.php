<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentLink;
use App\Services\PaymenntService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FastPaymentStatusCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:fast-check {--batch=50 : Batch size} {--concurrent=10 : Concurrent checks}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fast check payment status using batch processing';

    protected $paymenntService;

    public function __construct(PaymenntService $paymenntService)
    {
        parent::__construct();
        $this->paymenntService = $paymenntService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);

        $this->info('🚀 Starting FAST payment status check...');

        $batchSize = $this->option('batch');
        $concurrent = $this->option('concurrent');

        $this->info("📦 Batch size: {$batchSize}, Concurrent: {$concurrent}");

        // Get pending payments
        $query = PaymentLink::where('status', 'pending')
            ->needsStatusCheck(5);

        $totalPayments = $query->count();

        if ($totalPayments === 0) {
            $this->info('✅ No payments need checking!');
            return 0;
        }

        $this->info("🔍 Found {$totalPayments} payments to check");

        $updated = 0;
        $errors = 0;

        // Process in batches
        $query->chunk($batchSize, function ($chunk) use (&$updated, &$errors, $concurrent) {
            $this->processBatch($chunk, $concurrent, $updated, $errors);
        });

        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);

        $this->info("🎯 Fast check completed in {$executionTime} seconds!");
        $this->info("📊 Updated: {$updated}, Errors: {$errors}");

        return 0;
    }

    /**
     * Process a batch of payments
     */
    protected function processBatch($payments, $concurrent, &$updated, &$errors)
    {
        $this->info("📦 Processing batch of {$payments->count()} payments...");

        // Mark all as checked immediately
        $paymentIds = $payments->pluck('id')->toArray();
        PaymentLink::whereIn('id', $paymentIds)
            ->update(['last_status_check' => now()]);

        // Process payments
        foreach ($payments as $payment) {
            try {
                $result = $this->quickStatusUpdate($payment);
                if ($result) {
                    $updated++;
                    $this->info("✅ Payment {$payment->id} updated");
                }
            } catch (\Exception $e) {
                $errors++;
                $this->error("❌ Payment {$payment->id} failed: " . $e->getMessage());
            }
        }
    }

    /**
     * Quick status update
     */
    protected function quickStatusUpdate($payment)
    {
        // Quick API call
        $result = $this->paymenntService->getCheckoutStatus($payment->checkout_id);

        if ($result['success']) {
            $status = $result['data']['status'] ?? 'pending';
            $paidAt = null;

            if ($status === 'PAID') {
                $status = 'paid';
                $paidAt = now();
            } elseif ($status === 'CANCELLED') {
                $status = 'cancelled';
            } elseif ($status === 'EXPIRED') {
                $status = 'expired';
            }

            $payment->update([
                'status' => $status,
                'paid_at' => $paidAt
            ]);

            return true;
        }

        return false;
    }
}
