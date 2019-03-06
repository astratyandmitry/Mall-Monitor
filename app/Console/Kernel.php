<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\Import\IntegrateWebKassaCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\Import\IntegrateProsystemsMultiCommand;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'mallmonitor:integrate-prosystems-multi' => IntegrateProsystemsMultiCommand::class,
        'mallmonitor:integrate-webkassa' => IntegrateWebKassaCommand::class,
    ];


    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('mallmonitor:integrate-prosystems-multi')->hourly();
        $schedule->command('mallmonitor:integrate-webkassa')->hourly();
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
