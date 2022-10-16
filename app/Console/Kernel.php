<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ImportFTPChequesCommand;
use App\Console\Commands\CheckYesterdayChequesCommand;
use App\Console\Commands\Integration\IntegrateWebKassaCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\Integration\IntegrateProsystemsMultiCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'keruenmonitor:integrate-prosystems-multi' => IntegrateProsystemsMultiCommand::class,
        'keruenmonitor:integrate-webkassa' => IntegrateWebKassaCommand::class,
        'keruenmonitor:cheques-check-yesterday' => CheckYesterdayChequesCommand::class,
        'keruenmonitor:import-ftp-cheques' => ImportFTPChequesCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('keruenmonitor:integrate-prosystems-multi')->hourly();
        $schedule->command('keruenmonitor:integrate-webkassa')->hourly();
        $schedule->command('keruenmonitor:cheques-check-yesterday')->dailyAt('04:00');
        $schedule->command('keruenmonitor:import-ftp-cheques')->dailyAt('02:00');
        $schedule->command('keruenmonitor:clear-duplicate-cheques-prosystems --limit=5000')->hourly();
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
