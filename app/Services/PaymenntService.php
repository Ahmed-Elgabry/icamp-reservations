<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymenntService
{
    protected $apiKey;
    protected $apiSecret;
    protected $baseUrl;
    protected $isTest;

    public function __construct()
    {
        $this->apiKey = config('services.paymennt.api_key');
        $this->apiSecret = config('services.paymennt.api_secret');
        $this->isTest = config('services.paymennt.test_mode', true);
        $this->baseUrl = $this->isTest 
            ? 'https://api.test.paymennt.com/mer/v2.0'
            : 'https://api.paymennt.com/mer/v2.0';
    }

    /**
     * إنشاء رابط دفع جديد
     */
    public function createPaymentLink($data)
    {
        try {
            $payload = [
                'requestId' => $data['request_id'] ?? uniqid('PAY-'),
                'orderId' => $data['order_id'] ?? uniqid('ORD-'),
                'currency' => $data['currency'] ?? 'AED',
                'amount' => $data['amount'],
                'customer' => [
                    'id' => $data['customer_id'] ?? '1',
                    'firstName' => $data['customer_name'] ?? 'Customer',
                    'lastName' => '',
                    'email' => $data['customer_email'] ?? 'customer@example.com',
                    'phone' => $data['customer_phone'] ?? '+971500000000'
                ],
                'billingAddress' => [
                    'name' => $data['customer_name'] ?? 'Customer',
                    'address1' => $data['address'] ?? 'Address',
                    'city' => $data['city'] ?? 'Dubai',
                    'state' => $data['state'] ?? 'Dubai',
                    'zip' => $data['zip'] ?? '00000',
                    'country' => $data['country'] ?? 'AE'
                ],
                'returnUrl' => $data['return_url'] ?? route('payment.callback'),
                'language' => app()->getLocale() === 'ar' ? 'ar' : 'en'
            ];

            // إضافة العناصر إذا كانت متوفرة
            if (isset($data['items']) && is_array($data['items'])) {
                $payload['items'] = $data['items'];
            }

            // إضافة العنوان إذا كان مختلفاً
            if (isset($data['delivery_address'])) {
                $payload['deliveryAddress'] = $data['delivery_address'];
            }

            $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
                ->post($this->baseUrl . '/checkout/web', $payload);

            if ($response->successful()) {
                $result = $response->json();
                if ($result['success']) {
                    return [
                        'success' => true,
                        'data' => $result['result'],
                        'checkout_url' => $result['result']['redirectUrl'] ?? null,
                        'checkout_id' => $result['result']['id'] ?? null,
                        'checkout_key' => $result['result']['checkoutKey'] ?? null
                    ];
                }
            }

            Log::error('Paymennt API Error', [
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to create payment link',
                'details' => $response->json()
            ];

        } catch (\Exception $e) {
            Log::error('Paymennt Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * الحصول على حالة الدفع
     */
    public function getCheckoutStatus($checkoutId)
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
                ->get($this->baseUrl . '/checkout/' . $checkoutId);

            if ($response->successful()) {
                $result = $response->json();
                if ($result['success']) {
                    return [
                        'success' => true,
                        'data' => $result['result']
                    ];
                }
            }

            return [
                'success' => false,
                'error' => 'Failed to get checkout status'
            ];

        } catch (\Exception $e) {
            Log::error('Paymennt Get Status Error', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * إلغاء رابط الدفع
     */
    public function cancelCheckout($checkoutId)
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
                ->post($this->baseUrl . '/checkout/' . $checkoutId . '/cancel');

            if ($response->successful()) {
                $result = $response->json();
                return [
                    'success' => $result['success'] ?? false,
                    'data' => $result['result'] ?? null
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to cancel checkout'
            ];

        } catch (\Exception $e) {
            Log::error('Paymennt Cancel Error', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * إعادة إرسال رابط الدفع
     */
    public function resendCheckout($checkoutId)
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
                ->post($this->baseUrl . '/checkout/' . $checkoutId . '/resend');

            if ($response->successful()) {
                $result = $response->json();
                return [
                    'success' => $result['success'] ?? false,
                    'data' => $result['result'] ?? null
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to resend checkout'
            ];

        } catch (\Exception $e) {
            Log::error('Paymennt Resend Error', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Service error: ' . $e->getMessage()
            ];
        }
    }
}
