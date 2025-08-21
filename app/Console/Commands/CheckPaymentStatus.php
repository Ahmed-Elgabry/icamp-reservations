<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentLink;
use App\Services\PaymenntService;
use Illuminate\Support\Facades\Log;

class CheckPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-status {--force : Force check all payments}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check payment status for all pending payment links';

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
        $this->info('Starting payment status check...');

        // Get pending payment links
        $query = PaymentLink::where('status', 'pending');

        if (!$this->option('force')) {
            // Only check payments that haven't been checked recently (last 5 minutes)
            $query->where(function ($q) {
                $q->whereNull('last_status_check')
                    ->orWhere('last_status_check', '<=', now()->subMinutes(5));
            });
        }

        $paymentLinks = $query->get();

        $this->info("Found {$paymentLinks->count()} payment links to check");

        $updated = 0;
        $errors = 0;

        foreach ($paymentLinks as $paymentLink) {
            try {
                $this->info("Checking payment link ID: {$paymentLink->id} (Order: {$paymentLink->order_id})");

                $result = $this->paymenntService->getCheckoutStatus($paymentLink->checkout_id);

                if ($result['success']) {
                    $status = $result['data']['status'] ?? 'pending';
                    $paidAt = null;

                    if ($status === 'PAID') {
                        $status = 'paid';
                        $paidAt = now();
                        $this->info("✓ Payment {$paymentLink->id} is now PAID");
                    } elseif ($status === 'CANCELLED') {
                        $status = 'cancelled';
                        $this->info("✗ Payment {$paymentLink->id} was CANCELLED");
                    } elseif ($status === 'EXPIRED') {
                        $status = 'expired';
                        $this->info("✗ Payment {$paymentLink->id} has EXPIRED");
                    }

                    $paymentLink->update([
                        'status' => $status,
                        'paid_at' => $paidAt,
                        'last_status_check' => now()
                    ]);

                    $updated++;
                } else {
                    $this->error("Failed to get status for payment {$paymentLink->id}: " . ($result['error'] ?? 'Unknown error'));
                    $errors++;
                }

                // Add small delay to avoid overwhelming the API
                usleep(500000); // 0.5 seconds

            } catch (\Exception $e) {
                $this->error("Error checking payment {$paymentLink->id}: " . $e->getMessage());
                $errors++;

                Log::error('Payment Status Check Error', [
                    'payment_link_id' => $paymentLink->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Status check completed. Updated: {$updated}, Errors: {$errors}");

        return 0;
    }
}
