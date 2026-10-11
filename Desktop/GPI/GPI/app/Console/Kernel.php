<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Vérification quotidienne des fins de vie des équipements
        $schedule->command('equipements:check-lifetime')->dailyAt('08:00');
        // Vérification quotidienne des fins de vie des mobiliers
        $schedule->command('mobiliers:check-lifetime')->dailyAt('08:05');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
