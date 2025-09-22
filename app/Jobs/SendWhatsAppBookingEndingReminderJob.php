<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\WhatsAppService;
use App\Models\WhatsappMessageTemplate;
use App\Models\ServiceSiteAndCustomerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppBookingEndingReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Get the booking ending reminder template
            $template = WhatsappMessageTemplate::getByType('booking_ending_reminder');
            if (!$template) {
                Log::warning('No active booking ending reminder template found', ['order_id' => $this->order->id]);
                return;
            }

            // Check if customer has phone number
            if (!$this->order->customer || !$this->order->customer->phone) {
                Log::warning('Order customer has no phone number for WhatsApp booking ending reminder', [
                    'order_id' => $this->order->id,
                    'customer_id' => $this->order->customer_id
                ]);
                return;
            }

            // Prepare template data
            $customerName = $this->order->customer->name;
            $reservationNumber = $this->order->order_number;
            $checkoutTime = $this->order->time_to ?: '[وقت الخروج]';

            // Get worker phone from service site data
            $serviceSiteData = ServiceSiteAndCustomerService::getLatestForWhatsApp();
            $receptionPhone = $serviceSiteData['workerphone'] ?? '+971501234567'; // Use worker phone or default

            // Get bilingual message with placeholders replaced
            $message = $template->getBilingualMessage($customerName);

            // Replace additional placeholders specific to booking ending reminder
            $message = str_replace(['[رقم الحجز]', '[Reservation Number]'], $reservationNumber, $message);
            $message = str_replace(['[وقت الخروج]', '[check-out time]'], $checkoutTime, $message);
            $message = str_replace(['[رقم الهاتف]', '[phone number]'], $receptionPhone, $message);

            // Send WhatsApp message
            $whatsAppService = new WhatsAppService();
            $success = $whatsAppService->sendTextMessage(
                $this->order->customer->phone,
                $message
            );

            if ($success) {
                Log::info('WhatsApp booking ending reminder message sent successfully', [
                    'order_id' => $this->order->id,
                    'customer_phone' => $this->order->customer->phone,
                    'checkout_time' => $checkoutTime
                ]);
            } else {
                Log::error('Failed to send WhatsApp booking ending reminder message', [
                    'order_id' => $this->order->id,
                    'customer_phone' => $this->order->customer->phone
                ]);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp booking ending reminder job failed', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
