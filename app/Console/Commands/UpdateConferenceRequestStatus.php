<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ConferenceRequest;
use Carbon\Carbon;

class UpdateConferenceRequestStatus extends Command
{
    protected $signature = 'conference:update-status';
    protected $description = 'Update the status and availability of conference requests based on the current date and time and conference room';

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

                // Update availability of other requests with the same date, time, and conference room
                ConferenceRequest::where('conference_room_id', $request->conference_room_id)
                    ->where('date_start', $request->date_start)
                    ->where('time_start', $request->time_start)
                    ->where('id', '!=', $request->id)
                    ->update(['CAvailability' => 1]);
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

        $this->info('Conference request statuses and availability updated successfully.');
    }
}
