<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PaymentLink;
use App\Models\Order;
use App\Models\Customer;
use App\Services\PaymenntService;
use App\Services\WhatsAppService;
use App\Models\WhatsappMessageTemplate;
use App\Mail\PaymentLinkCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

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
                return response()->json([
                    'success' => false,
                    'message' => 'العميل غير صحيح'
                ]);
            }

            // Split customer name into first and last name
            $customerName = $customer->name ?? 'Customer Customer';
            Log::info('Original customer name: ' . $customerName);

            $nameParts = explode(' ', trim($customerName), 2);
            $firstName = $nameParts[0] ?? 'Customer';
            $lastName = isset($nameParts[1]) && !empty(trim($nameParts[1])) ? trim($nameParts[1]) : 'Customer';

            Log::info('Split names - First: ' . $firstName . ', Last: ' . $lastName);

            $paymentData = [
                'amount' => $request->amount,
                'description' => $request->description ?? 'Payment Link',
                'customer_id' => $customer->id,
                'customer_name' => $firstName,
                'customer_last_name' => $lastName,
                'customer_email' => $customer->email ?? 'customer@example.com',
                'customer_phone' => $customer->phone ?? '+971500000000',
                'order_id' => $request->order_id ?? uniqid('ORD-'),
                'currency' => 'AED',
                'send_email' => filter_var($request->send_email, FILTER_VALIDATE_BOOLEAN),
                'send_sms' => filter_var($request->send_sms, FILTER_VALIDATE_BOOLEAN),
                'expires_in' => $request->expires_at ? now()->diffInMinutes($request->expires_at) : null,
                'items' => [
                    [
                        'name' => $request->description ?? 'Payment Link',
                        'sku' => 'PAY-' . uniqid(),
                        'unitprice' => $request->amount,
                        'quantity' => 1,
                        'linetotal' => $request->amount
                    ]
                ]
            ];

            Log::info('Payment data being sent to service', $paymentData);
            $result = $this->paymenntService->createPaymentLink($paymentData);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'فشل في إنشاء رابط الدفع'
                ]);
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
                'handled_by' => auth()->id()
            ]);

            // Send email to customer if email exists and send_email is checked
            if ($customer->email && filter_var($request->send_email, FILTER_VALIDATE_BOOLEAN)) {
                try {
                    $emailData = [
                        'customer_name' => $customer->name ?? 'Customer',
                        'amount' => $request->amount,
                        'description' => $request->description,
                        'order_id' => $request->order_id,
                        'payment_url' => $result['checkout_url'],
                        'expires_at' => $request->expires_at ? \Carbon\Carbon::parse($request->expires_at) : null,
                    ];

                    Mail::to($customer->email)->send(new PaymentLinkCreated($emailData));

                    Log::info('Payment link email sent successfully', [
                        'customer_email' => $customer->email,
                        'payment_link_id' => $paymentLink->id,
                        'order_id' => $request->order_id
                    ]);
                } catch (\Exception $emailException) {
                    Log::error('Failed to send payment link email', [
                        'customer_email' => $customer->email,
                        'payment_link_id' => $paymentLink->id,
                        'error' => $emailException->getMessage()
                    ]);
                    // Don't fail the entire request if email fails
                }
            }

            // Send WhatsApp message if requested and customer has phone number
            if (filter_var($request->send_whatsapp, FILTER_VALIDATE_BOOLEAN) && $customer->phone) {
                try {
                    $this->sendPaymentLinkWhatsApp($customer, $result['checkout_url'], $request->amount, $request->description);
                    
                    Log::info('Payment link WhatsApp message sent successfully', [
                        'customer_phone' => $customer->phone,
                        'payment_link_id' => $paymentLink->id,
                        'order_id' => $request->order_id
                    ]);
                } catch (\Exception $whatsappException) {
                    Log::error('Payment link WhatsApp sending failed', [
                        'customer_phone' => $customer->phone,
                        'payment_link_id' => $paymentLink->id,
                        'error' => $whatsappException->getMessage()
                    ]);
                    // Don't fail the entire request if WhatsApp fails
                }
            }

            // Redirect to success page with payment link details
            return redirect()->route('bookings.payment-links.show-created', [
                'order_id' => $request->order_id,
                'payment_url' => $result['checkout_url'],
                'checkout_id' => $result['checkout_id'],
                'payment_link_id' => $paymentLink->id,
                'amount' => $request->amount,
                'description' => $request->description,
                'expires_at' => $request->expires_at,
                'email_sent' => filter_var($request->send_email, FILTER_VALIDATE_BOOLEAN) && $customer->email ? '1' : '0',
                'whatsapp_sent' => filter_var($request->send_whatsapp, FILTER_VALIDATE_BOOLEAN) && $customer->phone ? '1' : '0'
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Link Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء رابط الدفع'
            ]);
        }
    }

    /**
     * Show created payment link details
     */
    public function showCreated(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_url' => 'required|url',
            'checkout_id' => 'required|string',
            'payment_link_id' => 'required|integer',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|string',
        ]);

        $order = Order::with('customer')->findOrFail($request->order_id);
        $paymentLink = PaymentLink::findOrFail($request->payment_link_id);

        return view('dashboard.payment_links.show_created', [
            'order' => $order,
            'paymentLink' => $paymentLink,
            'payment_url' => $request->payment_url,
            'checkout_id' => $request->checkout_id,
            'payment_link_id' => $request->payment_link_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'expires_at' => $request->expires_at,
        ]);
    }
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
     * Resend email for payment link
     */
    public function resendEmail(PaymentLink $paymentLink)
    {
        try {
            // Load the payment link with customer relationship
            $paymentLink->load(['customer']);

            $customer = $paymentLink->customer;

            if (!$customer || !$customer->email) {
                Log::error('Customer or customer email not found', [
                    'payment_link_id' => $paymentLink->id,
                    'customer_id' => $paymentLink->customer_id,
                    'customer_exists' => $customer ? 'yes' : 'no',
                    'customer_email' => $customer ? $customer->email : 'null'
                ]);
                return response()->json([
                    'success' => false,
                    'message' => __('dashboard.customer_email_not_found')
                ]);
            }

            // Prepare email data - same structure as store method
            $emailData = [
                'customer_name' => $customer->name ?? 'Customer',
                'amount' => $paymentLink->amount,
                'description' => $paymentLink->description ?? 'Payment Link',
                'order_id' => $paymentLink->order_id,
                'payment_url' => $paymentLink->payment_url,
                'expires_at' => $paymentLink->expires_at ? \Carbon\Carbon::parse($paymentLink->expires_at) : null,
            ];

            Log::info('Resending payment link email', [
                'customer_email' => $customer->email,
                'payment_link_id' => $paymentLink->id,
                'email_data' => $emailData
            ]);

            // Send email to customer using log driver to avoid mailhog issues
            try {
                Mail::to($customer->email)->send(new PaymentLinkCreated($emailData));

                Log::info('Payment link email resent successfully via log driver', [
                    'customer_email' => $customer->email,
                    'payment_link_id' => $paymentLink->id
                ]);
            } catch (\Exception $emailException) {
                Log::error('Failed to resend payment link email even with log driver', [
                    'customer_email' => $customer->email,
                    'payment_link_id' => $paymentLink->id,
                    'error' => $emailException->getMessage()
                ]);
                // Don't fail the entire request if email fails
            }

            return response()->json([
                'success' => true,
                'message' => __('dashboard.payment_link_email_resent_success')
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Link Email Resend Error', [
                'payment_link_id' => $paymentLink->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('dashboard.payment_link_email_resent_error')
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
           
        ]);
    }

    /**
     * Update payment status
     */
    public function updateStatus(PaymentLink $paymentLink)
    {
        try {
            // Dispatch job to check payment status
            \App\Jobs\CheckPaymentStatusJob::dispatch($paymentLink->id);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال طلب فحص حالة الدفع. سيتم تحديث الحالة قريباً.'
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

    /**
     * Test email sending
     */
    public function testEmail()
    {
        try {
            Log::info('Testing email to osamabakry039@gmail.com');

            // Simple test email
            Mail::to('osamaeidbm1993@gmail.com')->send(new PaymentLinkCreated([
                'customer_name' => 'Test User',
                'amount' => '100.00',
                'description' => 'Test Payment Link',
                'order_id' => 'TEST-001',
                'payment_url' => 'https://example.com/test',
                'expires_at' => null,
            ]));

            Log::info('Test email sent successfully to osamabakry039@gmail.com');

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to osamabakry039@gmail.com'
            ]);
        } catch (\Exception $e) {
            Log::error('Test email failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Test email failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check all pending payment links status
     */
    public function checkAllStatus()
    {
        try {
            // Dispatch job to check all payment statuses
            \App\Jobs\CheckPaymentStatusJob::dispatch(null, true);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال طلب فحص حالة جميع المدفوعات. سيتم تحديث الحالات قريباً.'
            ]);
        } catch (\Exception $e) {
            Log::error('Check All Payment Status Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء فحص حالة المدفوعات'
            ]);
        }
    }

    /**
     * Send payment link WhatsApp message
     *
     * @param Customer $customer
     * @param string $paymentUrl
     * @param float $amount
     * @param string $description
     * @return void
     */
    private function sendPaymentLinkWhatsApp($customer, $paymentUrl, $amount, $description)
    {
        // Get the payment link created template
        $template = WhatsappMessageTemplate::getByType('payment_link_created');
        if (!$template) {
            Log::warning('No active payment link created template found');
            return;
        }

        // Get bilingual message
        $message = $template->getBilingualMessage($customer->name);
        
        // Replace payment link placeholder
        $message = str_replace(['[🔗 رابط الدفع]', '[🔗 Payment Link]'], $paymentUrl, $message);

        // Send WhatsApp message
        $whatsAppService = new WhatsAppService();
        $success = $whatsAppService->sendTextMessage($customer->phone, $message);

        if (!$success) {
            throw new \Exception('Failed to send WhatsApp message');
        }
    }

    /**
     * Resend payment link via WhatsApp
     */
    public function resendWhatsApp(Request $request, $id)
    {
        try {
            $paymentLink = PaymentLink::with(['customer', 'order'])->findOrFail($id);
            
            if (!$paymentLink->customer || !$paymentLink->customer->phone) {
                return response()->json([
                    'success' => false,
                    'message' => __('dashboard.customer_phone_not_found')
                ]);
            }

            // Get the payment link resend template
            $template = WhatsappMessageTemplate::getByType('payment_link_resend');
            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => __('dashboard.template_not_found')
                ]);
            }

            // Get bilingual message
            $message = $template->getBilingualMessage($paymentLink->customer->name);
            
            // Replace payment link placeholder
            $paymentUrl = $request->input('payment_url', $paymentLink->payment_url);
            $message = str_replace(['[🔗 رابط الدفع]', '[🔗 Payment Link]'], $paymentUrl, $message);

            // Send WhatsApp message
            $whatsAppService = new WhatsAppService();
            $success = $whatsAppService->sendTextMessage($paymentLink->customer->phone, $message);

            if ($success) {
                Log::info('Payment link WhatsApp resent successfully', [
                    'payment_link_id' => $paymentLink->id,
                    'customer_phone' => $paymentLink->customer->phone
                ]);

                return response()->json([
                    'success' => true,
                    'message' => __('dashboard.payment_link_whatsapp_resent_success')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('dashboard.payment_link_whatsapp_resent_error')
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Payment link WhatsApp resend failed', [
                'payment_link_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('dashboard.payment_link_whatsapp_resent_error')
            ]);
        }
    }
}
