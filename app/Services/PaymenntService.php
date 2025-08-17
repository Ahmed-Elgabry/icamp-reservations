<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymenntService
{
    protected $apiKey;
    protected $apiSecret;
    protected $apiId;
    protected $baseUrl;
    protected $isTest;

    public function __construct()
    {
        $this->apiKey = config('services.paymennt.api_key'); // pk_live_...
        $this->apiSecret = config('services.paymennt.api_secret'); // mer_...
        $this->apiId = config('services.paymennt.api_id'); // 17c368305a0ce997
        $this->isTest = config('services.paymennt.test_mode', true);
        $this->baseUrl = $this->isTest
            ? 'https://api.test.paymennt.com/mer/v2.0'
            : 'https://api.paymennt.com/mer/v2.0';
    }

    /**
     * Get HTTP client with proper authentication headers
     */
    private function getHttpClient()
    {
        return Http::withHeaders([
            'X-Paymennt-Api-Key' => $this->apiId, // Use the short API ID
            'X-Paymennt-Api-Secret' => $this->apiSecret, // Use the API secret
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
    }

    /**
     * إنشاء رابط دفع جديد
     */
    public function createPaymentLink($data)
    {
        try {
            // Ensure customer data is properly set
            $customerData = [
                'id' => (string)($data['customer_id'] ?? '1'),
                'firstName' => $data['customer_name'] ?? 'Customer',
                'lastName' => $data['customer_last_name'] ?? 'Customer',
                'email' => $data['customer_email'] ?? 'customer@example.com',
                'phone' => $data['customer_phone'] ?? '+971500000000'
            ];

            // Debug customer data
            Log::info('Customer data before API call', $customerData);

            $payload = [
                'requestId' => $data['request_id'] ?? uniqid('PAY-'),
                'orderId' => $data['order_id'] ?? uniqid('ORD-'),
                'description' => $data['description'] ?? 'Payment Link',
                'currency' => $data['currency'] ?? 'AED',
                'amount' => $data['amount'],
                'sendSms' => $data['send_sms'] ?? false,
                'sendEmail' => $data['send_email'] ?? false,
                'customer' => $customerData,
                'billingAddress' => [
                    'name' => ($data['customer_name'] ?? 'Customer') . ' ' . ($data['customer_last_name'] ?? 'Customer'),
                    'address1' => $data['address'] ?? 'Address',
                    'city' => $data['city'] ?? 'Dubai',
                    'state' => $data['state'] ?? 'Dubai',
                    'zip' => $data['zip'] ?? '00000',
                    'country' => $data['country'] ?? 'AE'
                ],
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

            // إضافة انتهاء الصلاحية
            if (isset($data['expires_in'])) {
                $payload['expiresIn'] = $data['expires_in'];
            }

            Log::info('Paymennt API Request', [
                'url' => $this->baseUrl . '/checkout/link',
                'payload' => $payload,
                'headers' => [
                    'X-Paymennt-Api-Key' => $this->apiId,
                    'X-Paymennt-Api-Secret' => substr($this->apiSecret, 0, 20) . '...'
                ]
            ]);

            $response = $this->getHttpClient()
                ->post($this->baseUrl . '/checkout/link', $payload);

            Log::info('Paymennt API Response', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

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
            $response = $this->getHttpClient()
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
            $response = $this->getHttpClient()
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
            $response = $this->getHttpClient()
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
