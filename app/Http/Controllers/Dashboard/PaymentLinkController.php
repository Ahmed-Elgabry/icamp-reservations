<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PaymentLink;
use App\Models\Order;
use App\Models\Customer;
use App\Services\PaymenntService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentLinkController extends Controller
{
    protected $paymenntService;

    public function __construct(PaymenntService $paymenntService)
    {
        $this->paymenntService = $paymenntService;
    }

    /**
     * Display payment links list
     */
    public function index(Request $request)
    {
        $query = PaymentLink::with(['order.customer']);

        // Filter by order if passed
        if ($request->has('order_id') && $request->order_id) {
            $query->where('order_id', $request->order_id);
        }

        $paymentLinks = $query->orderBy('created_at', 'desc')->get();

        // If there's filtering, get the order to display the title
        $filteredOrder = null;
        if ($request->has('order_id') && $request->order_id) {
            $filteredOrder = Order::find($request->order_id);
        }

        return view('dashboard.payment_links.index', compact('paymentLinks', 'filteredOrder'));
    }

    /**
     * Display payment link creation page
     */
    public function create(Request $request)
    {
        $orders = Order::with('customer')->get();
        $customers = Customer::all();

        // If order_id is passed from query parameter
        $selectedOrderId = $request->query('order_id');
        $selectedOrder = null;

        if ($selectedOrderId) {
            $selectedOrder = Order::with('customer')->find($selectedOrderId);
        }

        return view('dashboard.payment_links.create', compact('orders', 'customers', 'selectedOrder'));
    }

    /**
     * Create new payment link
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'order_id' => 'required|exists:orders,id',
            'description' => 'nullable|string|max:500',
            'expires_at' => 'nullable|date|after:now',
        ]);

        try {
            // Get order and customer data
            $order = Order::with('customer')->findOrFail($request->order_id);
            $customer = $order->customer;

            if (!$customer) {
                return back()->withErrors(['error' => __('dashboard.payment_link_errors.invalid_customer')]);
            }

            // Create payment link via Paymennt
            $paymentData = [
                'amount' => $request->amount,
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email ?? 'customer@example.com',
                'customer_phone' => $customer->phone ?? '+971500000000',
                'order_id' => $request->order_id ?? uniqid('ORD-'),
                'currency' => 'AED',
                'return_url' => route('payment.callback'),
                'items' => [
                    [
                        'name' => $request->description ?? __('dashboard.payment_link_defaults.payment'),
                        'sku' => 'PAY-' . uniqid(),
                        'unitprice' => $request->amount,
                        'quantity' => 1,
                        'linetotal' => $request->amount
                    ]
                ]
            ];

            $result = $this->paymenntService->createPaymentLink($paymentData);

            if (!$result['success']) {
                return back()->withErrors(['error' => __('dashboard.payment_link_errors.creation_failed', ['error' => $result['error'] ?? __('dashboard.payment_link_errors.unknown_error')])]);
            }

            // Save payment link to database
            $paymentLink = PaymentLink::create([
                'order_id' => $request->order_id,
                'customer_id' => $customer->id,
                'amount' => $request->amount,
                'description' => $request->description,
                'checkout_id' => $result['checkout_id'],
                'checkout_key' => $result['checkout_key'],
                'payment_url' => $result['checkout_url'],
                'status' => 'pending',
                'expires_at' => $request->expires_at,
                'request_id' => $result['data']['requestId'] ?? null,
                'order_id_paymennt' => $result['data']['orderId'] ?? null,
            ]);

            return redirect()->route('payment-links.index')
                ->withSuccess(__('dashboard.payment_link_creation_success'));
        } catch (\Exception $e) {
            Log::error('Payment Link Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => __('dashboard.payment_link_errors.creation_error')]);
        }
    }

    /**
     * Display payment link
     */
    public function show(PaymentLink $paymentLink)
    {
        return view('dashboard.payment_links.show', compact('paymentLink'));
    }

    /**
     * Resend payment link
     */
    public function resend(PaymentLink $paymentLink)
    {
        try {
            $result = $this->paymenntService->resendCheckout($paymentLink->checkout_id);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => __('dashboard.payment_link_resend_success')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __('dashboard.payment_link_errors.resend_failed')
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Link Resend Error', [
                'payment_link_id' => $paymentLink->id,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('dashboard.payment_link_errors.resend_error')
            ]);
        }
    }

    /**
     * Cancel payment link
     */
    public function cancel(PaymentLink $paymentLink)
    {
        try {
            $result = $this->paymenntService->cancelCheckout($paymentLink->checkout_id);

            if ($result['success']) {
                $paymentLink->update(['status' => 'cancelled']);

                return response()->json([
                    'success' => true,
                    'message' => __('dashboard.payment_link_cancel_success')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __('dashboard.payment_link_errors.cancel_failed')
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Link Cancel Error', [
                'payment_link_id' => $paymentLink->id,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('dashboard.payment_link_errors.cancel_error')
            ]);
        }
    }

    /**
     * Delete payment link
     */
    public function destroy(PaymentLink $paymentLink)
    {
        try {
            // Cancel the link in Paymennt if it's pending
            if ($paymentLink->status === 'pending') {
                $this->paymenntService->cancelCheckout($paymentLink->checkout_id);
            }

            $paymentLink->delete();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.payment_link_delete_success')
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Link Delete Error', [
                'payment_link_id' => $paymentLink->id,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('dashboard.payment_link_errors.delete_error')
            ]);
        }
    }

    /**
     * Generate QR Code for the link
     */
    public function qrCode(PaymentLink $paymentLink)
    {
        // QR Code will be added later after installing the package
        return response()->json([
            'success' => true,
            'url' => $paymentLink->payment_url,
            'message' => __('dashboard.payment_link_qr_code_coming_soon')
        ]);
    }

    /**
     * Copy payment link
     */
    public function copy(PaymentLink $paymentLink)
    {
        return response()->json([
            'success' => true,
            'url' => $paymentLink->payment_url,
            'message' => __('dashboard.payment_link_copy_success')
        ]);
    }

    /**
     * Update payment status
     */
    public function updateStatus(PaymentLink $paymentLink)
    {
        try {
            $result = $this->paymenntService->getCheckoutStatus($paymentLink->checkout_id);

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

                $paymentLink->update([
                    'status' => $status,
                    'paid_at' => $paidAt
                ]);

                return response()->json([
                    'success' => true,
                    'status' => $status,
                    'message' => __('dashboard.payment_link_status_update_success')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __('dashboard.payment_link_errors.status_update_failed')
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Link Status Update Error', [
                'payment_link_id' => $paymentLink->id,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('dashboard.payment_link_errors.status_update_error')
            ]);
        }
    }
}
