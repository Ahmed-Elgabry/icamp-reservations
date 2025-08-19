<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Survey;
use App\Mail\SendMail;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
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

        // Get the survey (assuming there's only one survey with ID 1)
        $survey = Survey::find(1);
        if (!$survey) {
            $this->error('No survey found!');
            return 1;
        }

        // Get orders that were completed 24 hours ago and haven't received a survey email yet
        $completedAt = Carbon::now()->subHours(24);
        $orders = Order::where('status', 'completed')
            ->where('updated_at', '<=', $completedAt)
            ->whereDoesntHave('surveyResponses') // Check if the order doesn't have any survey responses
            ->with('customer')
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            if (!$order->customer || !$order->customer->email) {
                $this->warn("Order #{$order->id} has no customer email. Skipping.");
                continue;
            }

            // Generate survey URL
            $surveyUrl = route('surveys.show', ['order' => $order->id]);

            // Prepare email data
            $emailData = [
                'title' => 'تقييم الخدمة - Service Evaluation',
                'body' => 'شكراً لاستخدامك خدماتنا. نرجو منك تقييم تجربتك من خلال الاستبيان التالي:
                         <br><br>
                         Thank you for using our services. Please evaluate your experience through the following survey:
                         <br><br>
                         <a href="' . $surveyUrl . '" style="padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">اضغط هنا للتقييم / Click here to evaluate</a>',
                'order' => $order,
                'surveyUrl' => $surveyUrl
            ];

            try {
                // Send the email
                Mail::to($order->customer->email)->send(new SendMail($emailData));
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
