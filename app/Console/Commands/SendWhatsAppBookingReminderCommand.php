<?php

namespace App\Console\Commands;

use App\Jobs\SendWhatsAppBookingReminderJob;
use App\Models\Order;
use App\Models\WhatsappMessageTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendWhatsAppBookingReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:send-booking-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp booking reminder messages to customers 3 days before their reservation date.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting WhatsApp booking reminder message sending...');

        try {
            // Check if booking reminder template is active
            $bookingReminderTemplate = WhatsappMessageTemplate::getByType('booking_reminder');
            if (!$bookingReminderTemplate) {
                $this->info('No active booking reminder template found. Please activate a booking reminder template to send WhatsApp messages.');
                return 0;
            }

            $this->info("Using booking reminder template: {$bookingReminderTemplate->name} (ID: {$bookingReminderTemplate->id})");

            // Calculate the date 3 days from now
            $targetDate = Carbon::now()->addDays(3)->format('Y-m-d');

            // Find approved orders with reservation date 3 days from now
            $orders = Order::with(['customer'])
                ->where('status', 'approved')
                ->whereDate('date', $targetDate)
                ->get();

            $this->info("Found {$orders->count()} orders with reservation date on {$targetDate}.");

            if ($orders->isEmpty()) {
                $this->info('No orders found for booking reminder today.');
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
                SendWhatsAppBookingReminderJob::dispatch($order);
                $this->info("Queued booking reminder for order {$order->id} (Customer: {$order->customer->name}, Date: {$order->date})");
                $sentCount++;
            }

            $this->info("Successfully queued {$sentCount} WhatsApp booking reminder messages.");

        } catch (\Exception $e) {
            $this->error('Error sending WhatsApp booking reminder messages: ' . $e->getMessage());
            Log::error('WhatsApp booking reminder command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }
}