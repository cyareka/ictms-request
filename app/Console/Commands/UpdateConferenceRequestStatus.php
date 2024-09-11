<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ConferenceRequest;
use Carbon\Carbon;
use Log;

class UpdateConferenceRequestStatus extends Command
{
    protected $signature = 'conference:update-status';
    protected $description = 'Update the status and availability of conference requests based on the current date and time and conference room';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();

        Log::info('Updating conference requests...');

        // Update Pending or For Approval to Not Approved if date_start and time_start have passed
        $pendingRequests = ConferenceRequest::whereIn('FormStatus', ['Pending', 'For Approval'])
            ->where(function($query) use ($now) {
                $query->where('date_start', '<', $now->toDateString())
                      ->orWhere(function($query) use ($now) {
                          $query->where('date_start', '=', $now->toDateString())
                                ->where('time_start', '<=', $now->toTimeString());
                      });
            })
            ->get();

        Log::info("Found {$pendingRequests->count()} pending requests to update");

        $pendingRequests->each(function($request) {
            Log::info("Updating request #{$request->id} to Not Approved");
            $request->update(['FormStatus' => 'Not Approved']);
        });

        // Update Ongoing events t  o Finished if date_end and time_end have passed
        $ongoingRequests = ConferenceRequest::where('FormStatus', 'Approved')
            ->where('EventStatus', 'Ongoing')
            ->where(function($query) use ($now) {
                $query->where('date_end', '<', $now->toDateString())
                      ->orWhere(function($query) use ($now) {
                          $query->where('date_end', '=', $now->toDateString())
                                ->where('time_end', '<=', $now->toTimeString());
                      });
            })
            ->get();

        Log::info("Found {$ongoingRequests->count()} ongoing requests to update");

        $ongoingRequests->each(function($request) {
            Log::info("Updating request #{$request->id} to Finished");
            $request->update(['EventStatus' => 'Finished']);
        });

        $this->info('Conference requests have been updated.');
    }
}
