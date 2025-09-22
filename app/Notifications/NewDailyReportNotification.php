<?php

namespace App\Notifications;

use App\Models\DailyReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDailyReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public DailyReport $report)
    {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'new_daily_report_title',
            'message' => 'new_daily_report_message',
            'report_id' => $this->report->id,
            'employee_name' => $this->report->employee->name,
            'employee_id' => $this->report->employee_id,
            'url' => route('daily-reports.show', $this->report),
        ];

    }
}
