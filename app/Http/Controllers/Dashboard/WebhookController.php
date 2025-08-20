<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentLink;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    /**
     * Handle Paymennt webhook
     */
    public function handle(Request $request)
    {
        try {
            Log::info('Paymennt webhook received', $request->all());

            // Verify webhook signature (important for security)
            if (!$this->verifyWebhookSignature($request)) {
                Log::warning('Invalid webhook signature');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $event = $request->input('event');
            $data = $request->input('data');

            switch ($event) {
                case 'checkout.paid':
                    $this->handlePaymentSuccess($data);
                    break;

                case 'checkout.cancelled':
                    $this->handlePaymentCancelled($data);
                    break;

                case 'checkout.expired':
                    $this->handlePaymentExpired($data);
                    break;

                default:
                    Log::info("Unhandled webhook event: {$event}");
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSuccess($data)
    {
        $checkoutId = $data['id'] ?? null;

        if (!$checkoutId) {
            Log::warning('No checkout ID in webhook data');
            return;
        }

        DB::transaction(function () use ($checkoutId, $data) {
            $paymentLink = PaymentLink::where('checkout_id', $checkoutId)->first();

            if (!$paymentLink) {
                Log::warning("Payment link not found for checkout: {$checkoutId}");
                return;
            }

            // Update payment status immediately
            $paymentLink->update([
                'status' => 'paid',
                'paid_at' => now(),
                'last_status_check' => now()
            ]);

            Log::info("Payment {$paymentLink->id} marked as PAID via webhook", [
                'checkout_id' => $checkoutId,
                'order_id' => $paymentLink->order_id
            ]);

            // Here you can add additional logic:
            // - Update order status
            // - Send notifications
            // - Create invoices
            // - Trigger other business processes
        });
    }

    /**
     * Handle cancelled payment
     */
    protected function handlePaymentCancelled($data)
    {
        $checkoutId = $data['id'] ?? null;

        if ($checkoutId) {
            $paymentLink = PaymentLink::where('checkout_id', $checkoutId)->first();

            if ($paymentLink) {
                $paymentLink->update([
                    'status' => 'cancelled',
                    'last_status_check' => now()
                ]);

                Log::info("Payment {$paymentLink->id} marked as CANCELLED via webhook");
            }
        }
    }

    /**
     * Handle expired payment
     */
    protected function handlePaymentExpired($data)
    {
        $checkoutId = $data['id'] ?? null;

        if ($checkoutId) {
            $paymentLink = PaymentLink::where('checkout_id', $checkoutId)->first();

            if ($paymentLink) {
                $paymentLink->update([
                    'status' => 'expired',
                    'last_status_check' => now()
                ]);

                Log::info("Payment {$paymentLink->id} marked as EXPIRED via webhook");
            }
        }
    }

    /**
     * Verify webhook signature (implement based on Paymennt docs)
     */
    protected function verifyWebhookSignature(Request $request)
    {
        // TODO: Implement signature verification based on Paymennt documentation
        // This is important for security

        $signature = $request->header('X-Paymennt-Signature');
        $payload = $request->getContent();

        // Verify signature logic here
        // For now, return true (not recommended for production)
        return true;
    }
}
