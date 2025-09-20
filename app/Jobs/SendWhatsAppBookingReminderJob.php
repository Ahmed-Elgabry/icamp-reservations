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

class SendWhatsAppBookingReminderJob implements ShouldQueue
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
            // Get the booking reminder template
            $template = WhatsappMessageTemplate::getByType('booking_reminder');
            if (!$template) {
                Log::warning('No active booking reminder template found', ['order_id' => $this->order->id]);
                return;
            }

            // Check if customer has phone number
            if (!$this->order->customer || !$this->order->customer->phone) {
                Log::warning('Order customer has no phone number for WhatsApp booking reminder', [
                    'order_id' => $this->order->id,
                    'customer_id' => $this->order->customer_id
                ]);
                return;
            }

            // Prepare template data
            $customerName = $this->order->customer->name;
            $reservationNumber = $this->order->order_number;
            $date = $this->order->date ? \Carbon\Carbon::parse($this->order->date)->format('Y-m-d') : '[التاريخ]';
            $startTime = $this->order->time_from ?: '[وقت البداية]';
            $endTime = $this->order->time_to ?: '[وقت النهاية]';
            $amountPaid = $this->order->price ?: '[المبلغ المدفوع]';
            $remainingAmount = $this->order->deposit ?: '[المبلغ المتبقي]';
            $insuranceAmount = $this->order->insurance_amount ?: '[مبلغ التأمين]';
            // Get service site data for worker information and location
            $serviceSiteData = ServiceSiteAndCustomerService::getLatestForWhatsApp();
            $receptionName = $serviceSiteData['workername_ar'] ?? 'Funcamp'; // Use worker name in Arabic
            $receptionPhone = $serviceSiteData['workerphone'] ?? '+971501234567'; // Use worker phone
            
            // Generate location link from service site data
            $serviceSite = $serviceSiteData['service_site'] ?? 'مخيم الوادي الأخضر - دبي';
            $locationLink = $serviceSite; // Generate Google Maps link

            // Get bilingual message with placeholders replaced
            $message = $template->getBilingualMessage($customerName);
            
            // Replace additional placeholders specific to booking reminder
            $message = str_replace(['[رقم الحجز]', '[Reservation Number]'], $reservationNumber, $message);
            $message = str_replace(['[التاريخ]', '[Date]'], $date, $message);
            $message = str_replace(['[وقت البداية]', '[Start Time]'], $startTime, $message);
            $message = str_replace(['[وقت النهاية]', '[End Time]'], $endTime, $message);
            $message = str_replace(['[المبلغ المدفوع]', '[Amount Paid]'], $amountPaid, $message);
            $message = str_replace(['[المبلغ المتبقي]', '[Remaining Amount]'], $remainingAmount, $message);
            $message = str_replace(['[مبلغ التأمين]', '[Security Deposit]'], $insuranceAmount, $message);
            $message = str_replace(['[رابط اللوكيشن]', '[Location Link]'], $locationLink, $message);
            $message = str_replace(['[الاسم]', '[Name]'], $receptionName, $message);
            $message = str_replace(['[رقم الهاتف]', '[Phone Number]'], $receptionPhone, $message);

            // Send WhatsApp message
            $whatsAppService = new WhatsAppService();
            $success = $whatsAppService->sendTextMessage(
                $this->order->customer->phone,
                $message
            );

            if ($success) {
                Log::info('WhatsApp booking reminder message sent successfully', [
                    'order_id' => $this->order->id,
                    'customer_phone' => $this->order->customer->phone,
                    'reservation_date' => $date
                ]);
            } else {
                Log::error('Failed to send WhatsApp booking reminder message', [
                    'order_id' => $this->order->id,
                    'customer_phone' => $this->order->customer->phone
                ]);
            }

        } catch (\Exception $e) {
            Log::error('WhatsApp booking reminder job failed', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}