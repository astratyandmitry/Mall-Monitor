<?php

namespace App\Console;

use App\Console\Commands\Import\IntegrateTrinityCommand;
use App\Console\Commands\Integration\IntegrateProsystemsMultiCommand;
use App\Console\Commands\Integration\IntegrateWebKassaCommand;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ImportFTPChequesCommand;
use App\Console\Commands\CheckYesterdayChequesCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        'keruenmonitor:integrate-trinity' => IntegrateTrinityCommand::class,
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
        $schedule->command('keruenmonitor:integrate-prosystems-multi')->hourlyAt(15);
        $schedule->command('keruenmonitor:integrate-webkassa')->hourlyAt(30);
        $schedule->command('keruenmonitor:integrate-trinity')->hourlyAt(45);

        $schedule->command('keruenmonitor:cheques-check-yesterday')->dailyAt('04:00');
        $schedule->command('keruenmonitor:import-ftp-cheques')->dailyAt('02:00');
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
