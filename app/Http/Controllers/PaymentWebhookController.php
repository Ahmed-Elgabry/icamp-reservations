<?php

namespace App\Http\Controllers;

use App\Models\PaymentLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class PaymentWebhookController extends Controller
{
    /**
     * Handle Webhook from Paymennt
     */
    public function handle(Request $request)
    {
        try {
            // Verify Webhook signature
            if (!$this->verifyWebhook($request)) {
                Log::warning('Invalid webhook signature', [
                    'headers' => $request->headers->all(),
                    'body' => $request->all()
                ]);
                return response('Unauthorized', 401);
            }

            $payload = $request->all();
            Log::info('Paymennt Webhook received', $payload);

            // Handle event
            switch ($payload['event'] ?? '') {
                case 'checkout.completed':
                    return $this->handleCheckoutCompleted($payload);

                case 'checkout.cancelled':
                    return $this->handleCheckoutCancelled($payload);

                case 'checkout.expired':
                    return $this->handleCheckoutExpired($payload);

                default:
                    Log::info('Unhandled webhook event', ['event' => $payload['event'] ?? 'unknown']);
                    return response('OK', 200);
            }
        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);

            return response('Internal Server Error', 500);
        }
    }

    /**
     * Verify Webhook signature
     */
    protected function verifyWebhook(Request $request)
    {
        $signature = $request->header('X-Paymennt-Signature');
        $webhookSecret = config('services.paymennt.webhook_secret');

        if (!$signature || !$webhookSecret) {
            return false;
        }

        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Handle payment completion
     */
    protected function handleCheckoutCompleted($payload)
    {
        $checkoutId = $payload['data']['id'] ?? null;

        if (!$checkoutId) {
            Log::error('Missing checkout ID in webhook', $payload);
            return response('Bad Request', 400);
        }

        $paymentLink = PaymentLink::where('checkout_id', $checkoutId)->first();

        if (!$paymentLink) {
            Log::error('Payment link not found', ['checkout_id' => $checkoutId]);
            return response('Not Found', 404);
        }

        // Update payment status
        $paymentLink->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);

        Log::info('Payment completed', [
            'payment_link_id' => $paymentLink->id,
            'checkout_id' => $checkoutId,
            'amount' => $paymentLink->amount
        ]);

        // Here you can add notifications or additional actions
        // For example, sending SMS or Email to the customer

        return response('OK', 200);
    }

    /**
     * Handle payment cancellation
     */
    protected function handleCheckoutCancelled($payload)
    {
        $checkoutId = $payload['data']['id'] ?? null;

        if (!$checkoutId) {
            Log::error('Missing checkout ID in webhook', $payload);
            return response('Bad Request', 400);
        }

        $paymentLink = PaymentLink::where('checkout_id', $checkoutId)->first();

        if (!$paymentLink) {
            Log::error('Payment link not found', ['checkout_id' => $checkoutId]);
            return response('Not Found', 404);
        }

        // Update payment status
        $paymentLink->update(['status' => 'cancelled']);

        Log::info('Payment cancelled', [
            'payment_link_id' => $paymentLink->id,
            'checkout_id' => $checkoutId
        ]);

        return response('OK', 200);
    }

    /**
     * Handle payment expiration
     */
    protected function handleCheckoutExpired($payload)
    {
        $checkoutId = $payload['data']['id'] ?? null;

        if (!$checkoutId) {
            Log::error('Missing checkout ID in webhook', $payload);
            return response('Bad Request', 400);
        }

        $paymentLink = PaymentLink::where('checkout_id', $checkoutId)->first();

        if (!$paymentLink) {
            Log::error('Payment link not found', ['checkout_id' => $checkoutId]);
            return response('Not Found', 404);
        }

        // Update payment status
        $paymentLink->update(['status' => 'expired']);

        Log::info('Payment expired', [
            'payment_link_id' => $paymentLink->id,
            'checkout_id' => $checkoutId
        ]);

        return response('OK', 200);
    }
}
