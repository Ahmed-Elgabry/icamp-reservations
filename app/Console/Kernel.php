<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{



    protected $commands = [
        Commands\crud::class,
        Commands\SendSurveyEmails::class,
        Commands\CheckPaymentStatus::class,
        Commands\FastPaymentStatusCheck::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('survey:send-emails')->dailyAt('09:00');
        $schedule->command('survey:send-emails')->everyMinute();

        // فحص حالة المدفوعات كل دقيقتين
        $schedule->command('payments:check-status')->everyMinute();

        // * * * * * php /home/USERNAME/yourproject/artisan schedule:run >> /dev/null 2>&1

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
