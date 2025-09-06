<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Survey;
use App\Models\SurveyEmailLog;
use App\Mail\SurveyEmail;
use App\Mail\SendMail;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSurveyEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send survey emails to customers 24 hours after their order is completed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to send survey emails...');
        $count = 0;
        // Get the survey (assuming there's only one survey with ID 1)
        $survey = Survey::find(1);
        if (!$survey) {
            $this->error('No survey found!');
            return 1;
        }

        // Get survey settings
        $survey = Survey::find(1);
        $settings = $survey->settings ?? [];

        // Check if survey emails are enabled
        if (!isset($settings['enabled']) || !$settings['enabled']) {
            $this->info('Survey emails are disabled in settings.');
            return 0;
        }

        // Get days after completion and send time from settings
        $daysAfterCompletion = $settings['days_after_completion'] ?? 1;
        $sendTime = $settings['send_time'] ?? '15:00';

        // Calculate completedAt based on settings
        $completedAt = Carbon::now()->subDays($daysAfterCompletion);

        // Check if current time matches the send time
        $currentTime = Carbon::now()->format('H:i');
        if ($currentTime !== $sendTime) {
            $this->info("Current time ($currentTime) does not match send time ($sendTime). Skipping.");
            // return 0;
        }
        $orders = Order::where('status', 'completed')
            ->where('updated_at', '<=', $completedAt)
            ->whereDoesntHave('surveyResponses') // Check if the order doesn't have any survey responses
            ->whereDoesntHave('surveyEmailLog') // Check if the order doesn't have any survey email logs
            ->with('customer')
            ->get();
        $this->info("Orders found: " . count($orders));

        foreach ($orders as $order) {
            if (!$order->customer || !$order->customer->email) {
                $this->warn("Order #{$order->id} has no customer email. Skipping.");
                continue;
            }

            // Generate survey URL
            $surveyUrl = route('surveys.public', ['order' => $order->id]);

            // Prepare email data
            $emailData = [
                'title' => 'تقييم نوع المخيم - Service Evaluation',
                'body' => 'شكراً لاستخدامك خدماتنا. نرجو منك تقييم تجربتك من خلال الاستبيان التالي:
                         <br><br>
                         Thank you for using our services. Please evaluate your experience through the following survey:
                         <br><br>
                         <a href="' . $surveyUrl . '" style="padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">اضغط هنا للتقييم / Click here to evaluate</a>',
                'order' => $order,
                'surveyUrl' => $surveyUrl
            ];

            try {
                // Send the email using our new SurveyEmail class
                Mail::to($order->customer->email)->send(new SurveyEmail($order, $surveyUrl));

                // Log that we've sent the email
                SurveyEmailLog::create([
                    'order_id' => $order->id,
                    'sent_at' => now()
                ]);

                $this->info("Survey email sent to customer for order #{$order->id}");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to send survey email for order #{$order->id}: " . $e->getMessage());
            }
        }

        $this->info("Survey email sending completed. Sent {$count} emails.");
        return 0;
    }
}
