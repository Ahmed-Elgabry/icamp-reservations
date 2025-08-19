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
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      
        // فحص حالة المدفوعات كل دقيقتين
        $schedule->command('payments:check-status')->everyMinute();


        $schedule->command('survey:send-emails')
            ->everyMinute()
            ->appendOutputTo(storage_path('logs/survey.log'));

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
