<?php

namespace App\Console;

use App\Jobs\CheckInstallmentsPaymentsJob;
use App\Jobs\SendTransactionsNotificationsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
//        $schedule->job(new CheckInstallmentsPaymentsJob())->monthly();
        $schedule->job(new SendTransactionsNotificationsJob())->everyTwoMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
