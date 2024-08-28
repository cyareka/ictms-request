<?php

// Create a new command for the scheduled task
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ConferenceRequest;
use Carbon\Carbon;

class UpdateConferenceRequestStatus extends Command
{
    protected $signature = 'conference:update-status';
    protected $description = 'Update the status of conference requests based on the current date and time';

    public function handle(): void
    {
        $currentDateTime = Carbon::now();

        // Fetch all "Approved/Ongoing" requests
        $ongoingRequests = ConferenceRequest::where('FormStatus', 'Approved')
            ->where('EventStatus', 'Ongoing')
            ->get();

        foreach ($ongoingRequests as $request) {
            if ($currentDateTime->greaterThanOrEqualTo(Carbon::parse($request->date_end . ' ' . $request->time_end))) {
                $request->update([
                    'EventStatus' => 'Finished'
                ]);
            }
        }

        // Fetch all "Pending" requests
        $pendingRequests = ConferenceRequest::where('FormStatus', 'Pending')
            ->get();

        foreach ($pendingRequests as $request) {
            if ($currentDateTime->greaterThanOrEqualTo(Carbon::parse($request->date_end . ' ' . $request->time_end))) {
                $request->update([
                    'FormStatus' => 'Not Approved'
                ]);
            }
        }
        $this->info('Conference request statuses updated successfully.');
    }
}
