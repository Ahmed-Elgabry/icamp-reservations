<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $instanceId;
    protected $apiUrl;
    protected $token;

    public function __construct()
    {
        $this->instanceId = config('services.hypersender.instance_id');
        $this->apiUrl = config('services.hypersender.api_url', 'https://app.hypersender.com');
        $this->token = config('services.hypersender.token');
    }

    /**
     * Send a text message via WhatsApp using Send Text Safe endpoint
     * URL: https://app.hypersender.com/api/whatsapp/v1/:instance/send-text-safe
     * Based on: https://docs.hypersender.com/docs/whatsapp/wa-send-text-safe-message
     */
    public function sendTextMessage($phoneNumber, $message)
    {
        try {
            $response = Http::withToken($this->token)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl . '/api/whatsapp/v1/' . $this->instanceId . '/send-text-safe', [
                    'chatId' => $this->formatPhoneNumber($phoneNumber),
                    'text' => $message
                ]);

            Log::info('WhatsApp text message sent', [
                'phone' => $phoneNumber,
                'chat_id' => $this->formatPhoneNumber($phoneNumber),
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'response_json' => $response->json(),
                'headers' => $response->headers()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp text message failed', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send a file via WhatsApp using Send File endpoint
     * URL: https://app.hypersender.com/api/whatsapp/v1/:instance/send-file
     * Based on: https://docs.hypersender.com/docs/whatsapp/wa-send-file
     */
    public function sendFile($phoneNumber, $filePath, $caption = null)
    {
        try {
            $response = Http::withToken($this->token)
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->attach('file', file_get_contents($filePath), basename($filePath))
                ->post($this->apiUrl . '/api/whatsapp/v1/' . $this->instanceId . '/send-file', [
                    'chatId' => $this->formatPhoneNumber($phoneNumber),
                    'caption' => $caption
                ]);

            Log::info('WhatsApp file sent', [
                'phone' => $phoneNumber,
                'chat_id' => $this->formatPhoneNumber($phoneNumber),
                'file' => $filePath,
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'response_json' => $response->json(),
                'headers' => $response->headers()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp file send failed', [
                'phone' => $phoneNumber,
                'file' => $filePath,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send a link with preview via WhatsApp using Send Link Preview endpoint
     * URL: https://app.hypersender.com/api/whatsapp/v1/:instance/send-link-preview
     * Based on: https://docs.hypersender.com/docs/whatsapp/wa-send-link-preview
     */
    public function sendLinkPreview($phoneNumber, $url, $message = null)
    {
        try {
            $response = Http::withToken($this->token)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl . '/api/whatsapp/v1/' . $this->instanceId . '/send-link-preview', [
                    'chatId' => $this->formatPhoneNumber($phoneNumber),
                    'url' => $url,
                    'title' => $message
                ]);

            Log::info('WhatsApp link preview sent', [
                'phone' => $phoneNumber,
                'chat_id' => $this->formatPhoneNumber($phoneNumber),
                'url' => $url,
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'response_json' => $response->json(),
                'headers' => $response->headers()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp link preview failed', [
                'phone' => $phoneNumber,
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Format phone number for WhatsApp chat_id (country_code + phone_number + @c.us)
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If phone doesn't start with country code, assume UAE (+971)
        // if (!str_starts_with($phone, '971') && !str_starts_with($phone, '+971')) {
        //     // Remove leading zero if present
        //     $phone = ltrim($phone, '0');
        //     $phone = '971' . $phone;
        // }
        
        // Remove + if present
        $phone = ltrim($phone, '+');
        
        // Format as WhatsApp chat_id
        return $phone . '@c.us';
    }

    /**
     * Get formatted phone number with + prefix for display
     */
    public function getDisplayPhoneNumber($phoneNumber)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If phone doesn't start with country code, assume UAE (+971)
        // if (!str_starts_with($phone, '971') && !str_starts_with($phone, '+971')) {
        //     // Remove leading zero if present
        //     $phone = ltrim($phone, '0');
        //     $phone = '971' . $phone;
        // }
        
        // Remove + if present
        $phone = ltrim($phone, '+');
        
        return $phone;
    }
}
