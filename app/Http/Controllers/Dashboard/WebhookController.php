<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentLink;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WebhookController extends Controller
{
    /**
     * Handle Paymennt webhook
     */
    public function handle(Request $request)
    {
        try {
            $webhookData = $request->all();
            Log::info('Paymennt webhook received', $webhookData);

            // Verify webhook signature (important for security)
            if (!$this->verifyWebhookSignature($request)) {
                Log::warning('Invalid webhook signature');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            // Paymennt sends data directly in the request body
            $data = $webhookData;
            $status = $data['status'] ?? null;
            $checkoutId = $data['id'] ?? null;

            if (!$checkoutId) {
                Log::warning('No checkout ID in webhook data', $data);
                return response()->json(['error' => 'No checkout ID'], 400);
            }

            if (!$status) {
                Log::warning('No status in webhook data', $data);
                return response()->json(['error' => 'No status'], 400);
            }

            Log::info("Processing webhook for checkout {$checkoutId} with status {$status}");

            switch ($status) {
                case 'PAID':
                    $this->handlePaymentSuccess($data);
                    break;

                case 'PENDING':
                    $this->handlePaymentPending($data);
                    break;

                case 'CANCELLED':
                    $this->handlePaymentCancelled($data);
                    break;

                case 'EXPIRED':
                    $this->handlePaymentExpired($data);
                    break;

                case 'FAILED':
                    // Treat failed payments as cancelled since 'failed' status is not supported in the database
                    $this->handlePaymentCancelled($data);
                    break;

                default:
                    Log::info("Unhandled webhook status: {$status}", $data);
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
                'paid_at' => $data['paidOn'] ? Carbon::parse($data['paidOn']) : now(),
                'last_status_check' => now()
            ]);

            Log::info("Payment {$paymentLink->id} marked as PAID via webhook", [
                'checkout_id' => $checkoutId,
                'order_id' => $paymentLink->order_id ?? 'N/A',
                'amount' => $data['grandtotal'] ?? 'N/A',
                'currency' => $data['currency'] ?? 'N/A'
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

                Log::info("Payment {$paymentLink->id} marked as CANCELLED via webhook", [
                    'checkout_id' => $checkoutId,
                    'order_id' => $paymentLink->order_id ?? 'N/A'
                ]);
            } else {
                Log::warning("Payment link not found for cancelled checkout: {$checkoutId}");
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

                Log::info("Payment {$paymentLink->id} marked as EXPIRED via webhook", [
                    'checkout_id' => $checkoutId,
                    'order_id' => $paymentLink->order_id ?? 'N/A'
                ]);
            } else {
                Log::warning("Payment link not found for expired checkout: {$checkoutId}");
            }
        }
    }



    /**
     * Handle pending payment
     */
    protected function handlePaymentPending($data)
    {
        $checkoutId = $data['id'] ?? null;

        if ($checkoutId) {
            $paymentLink = PaymentLink::where('checkout_id', $checkoutId)->first();

            if ($paymentLink) {
                $paymentLink->update([
                    'status' => 'pending',
                    'last_status_check' => now()
                ]);

                Log::info("Payment {$paymentLink->id} marked as PENDING via webhook", [
                    'checkout_id' => $checkoutId,
                    'order_id' => $paymentLink->order_id ?? 'N/A',
                    'amount' => $data['grandtotal'] ?? 'N/A',
                    'currency' => $data['currency'] ?? 'N/A',
                    'customer_email' => $data['customerEmail'] ?? 'N/A',
                    'customer_name' => ($data['customerFirstName'] ?? '') . ' ' . ($data['customerLastName'] ?? '')
                ]);
            } else {
                Log::warning("Payment link not found for pending checkout: {$checkoutId}", [
                    'checkout_id' => $checkoutId,
                    'reference_id' => $data['referenceId'] ?? 'N/A',
                    'order_id' => $data['orderId'] ?? 'N/A'
                ]);
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
