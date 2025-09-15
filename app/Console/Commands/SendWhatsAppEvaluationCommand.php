<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Survey;
use App\Jobs\SendWhatsAppEvaluationJob;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendWhatsAppEvaluationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:send-evaluation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp evaluation messages to customers based on survey settings timing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting WhatsApp evaluation message sending...');
        
        try {
            // Get survey settings
            $survey = Survey::find(1);
            if (!$survey) {
                $this->error('No survey found!');
                return 1;
            }

            $settings = $survey->settings ?? [];

            // Check if evaluation template is active
            $evaluationTemplate = \App\Models\WhatsappMessageTemplate::getByType('evaluation');
            if (!$evaluationTemplate) {
                $this->info('No active evaluation template found. Please activate an evaluation template to send WhatsApp messages.');
                return 0;
            }

            $this->info("Using evaluation template: {$evaluationTemplate->name} (ID: {$evaluationTemplate->id})");

            // Get days after completion and send time from settings
            $daysAfterCompletion = $settings['days_after_completion'] ?? 1;
            $sendTime = $settings['send_time'] ?? '20:00';

            // Calculate the date when orders should have been completed
            $completedAt = Carbon::now()->subDays($daysAfterCompletion);

            // Check if current time matches the send time (within 2 minute tolerance)
            $currentTime = Carbon::now();
            $targetTime = Carbon::now()->setTimeFromTimeString($sendTime);
            $timeDifference = abs($currentTime->diffInMinutes($targetTime));
            
            if ($timeDifference > 2) {
                $this->info("Current time ({$currentTime->format('H:i')}) doesn't match send time ({$sendTime}). Skipping...");
                return 0;
            }

            // Find completed orders eligible for WhatsApp evaluation
            $orders = Order::with(['customer'])
                ->where('status', 'completed')
                ->whereDate('updated_at', '<=', $completedAt->toDateString())
                ->get();

            $this->info("Found {$orders->count()} orders eligible for WhatsApp evaluation.");

            $sentCount = 0;
            foreach ($orders as $order) {
                // Check if customer has phone number
                if (!$order->customer || !$order->customer->phone) {
                    $this->warn("Order {$order->id} has no customer phone number. Skipping...");
                    continue;
                }

                // Dispatch the job
                SendWhatsAppEvaluationJob::dispatch($order);
                $sentCount++;

                $this->info("Queued WhatsApp evaluation for order {$order->id} (Customer: {$order->customer->name})");
            }

            $this->info("Successfully queued {$sentCount} WhatsApp evaluation messages.");
            return 0;

        } catch (\Exception $e) {
            $this->error("Command failed: " . $e->getMessage());
            Log::error('WhatsApp evaluation command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}