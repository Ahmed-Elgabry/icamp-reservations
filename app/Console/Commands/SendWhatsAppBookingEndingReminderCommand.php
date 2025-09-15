<?php

namespace App\Console\Commands;

use App\Jobs\SendWhatsAppBookingEndingReminderJob;
use App\Models\Order;
use App\Models\WhatsappMessageTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendWhatsAppBookingEndingReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:send-booking-ending-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp booking ending reminder messages to customers 1 hour before their reservation end time.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting WhatsApp booking ending reminder message sending...');

        try {
            // Check if booking ending reminder template is active
            $bookingEndingTemplate = WhatsappMessageTemplate::getByType('booking_ending_reminder');
            if (!$bookingEndingTemplate) {
                $this->info('No active booking ending reminder template found. Please activate a booking ending reminder template to send WhatsApp messages.');
                return 0;
            }

            $this->info("Using booking ending reminder template: {$bookingEndingTemplate->name} (ID: {$bookingEndingTemplate->id})");

            // Get current time and calculate 1 hour from now
            $currentTime = Carbon::now();
            $oneHourFromNow = $currentTime->copy()->addHour();

            // Find approved orders with end time around 1 hour from now (within 5 minutes tolerance)
            $orders = Order::with(['customer'])
                ->where('status', 'approved')
                ->whereNotNull('time_to')
                ->whereTime('time_to', '>=', $oneHourFromNow->copy()->subMinutes(5)->format('H:i:s'))
                ->whereTime('time_to', '<=', $oneHourFromNow->copy()->addMinutes(5)->format('H:i:s'))
                ->get();

            $this->info("Found {$orders->count()} orders with end time around {$oneHourFromNow->format('H:i')}.");

            if ($orders->isEmpty()) {
                $this->info('No orders found for booking ending reminder at this time.');
                return 0;
            }

            $sentCount = 0;
            foreach ($orders as $order) {
                // Check if customer has phone number
                if (!$order->customer || !$order->customer->phone) {
                    $this->warn("Order {$order->id} - Customer has no phone number, skipping...");
                    continue;
                }

                // Dispatch the job to send the WhatsApp message
                SendWhatsAppBookingEndingReminderJob::dispatch($order);
                $this->info("Queued booking ending reminder for order {$order->id} (Customer: {$order->customer->name}, End Time: {$order->time_to})");
                $sentCount++;
            }

            $this->info("Successfully queued {$sentCount} WhatsApp booking ending reminder messages.");

        } catch (\Exception $e) {
            $this->error('Error sending WhatsApp booking ending reminder messages: ' . $e->getMessage());
            Log::error('WhatsApp booking ending reminder command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }
}