<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\WhatsAppService;
use App\Models\WhatsappMessageTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppEvaluationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @param Order $order
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
            // Get the evaluation template (already checked in command, but double-check here)
            $template = WhatsappMessageTemplate::getByType('evaluation');
            if (!$template) {
                Log::warning('No active evaluation template found', ['order_id' => $this->order->id]);
                return;
            }

            // Check if customer has phone number
            if (!$this->order->customer || !$this->order->customer->phone) {
                Log::warning('Order customer has no phone number for WhatsApp evaluation', [
                    'order_id' => $this->order->id,
                    'customer_id' => $this->order->customer_id
                ]);
                return;
            }

            // Generate survey URL
            $surveyUrl = route('surveys.public', ['order' => $this->order->id]);
            
            // Get bilingual message
            $message = $template->getBilingualMessage($this->order->customer->name, $surveyUrl);

            // Send WhatsApp message
            $whatsAppService = new WhatsAppService();
            $success = $whatsAppService->sendLinkPreview(
                $this->order->customer->phone,
                $surveyUrl,
                $message
            );

            if ($success) {
                Log::info('WhatsApp evaluation message sent successfully', [
                    'order_id' => $this->order->id,
                    'customer_phone' => $this->order->customer->phone,
                    'survey_url' => $surveyUrl
                ]);
            } else {
                Log::error('Failed to send WhatsApp evaluation message', [
                    'order_id' => $this->order->id,
                    'customer_phone' => $this->order->customer->phone
                ]);
            }

        } catch (\Exception $e) {
            Log::error('WhatsApp evaluation job failed', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}