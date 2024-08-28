<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Schedule the command to run every hour
        $schedule->command('conference:update-status')->hourly();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        // Register the command
        $this->commands([
            \App\Console\Commands\UpdateConferenceRequestStatus::class,
        ]);
    }
}
