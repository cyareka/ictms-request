<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the application logs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        File::cleanDirectory(storage_path('logs'));
        $this->info('Logs have been cleared!');
        return 0;
    }
}
