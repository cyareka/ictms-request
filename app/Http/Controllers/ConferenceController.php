<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\ConferenceRoom;
use App\Models\ConferenceRequest;
use App\Helpers\IDGenerator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Throwable;

class ConferenceController extends Controller
{
    /**
     * Generates a unique ID for a conference request.
     *
     * This function uses the IDGenerator class to generate a 10-character unique ID.
     * It ensures the generated ID does not already exist in the ConferenceRequest table.
     *
     * @return string The generated unique ID.
     */
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_10();
        } while (ConferenceRequest::query()->where('CRequestID', $generatedID)->exists());

        return $generatedID;
    }

    /**
     * Submits the conference room request form.
     *
     * This function validates the request data, checks for duplicate dates, and creates conference requests.
     * It handles file uploads for the requester's signature and stores the data in the database.
     * If validation or any other error occurs, it logs the error and redirects back with an error message.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing form data.
     * @return \Illuminate\Http\RedirectResponse The response object redirecting back to the form with a success or error message.
     */
    public function submitCForm(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'officeName' => 'required|string|exists:offices,OfficeID',
                'purpose' => 'required|string|max:255',
                'date_start.*' => 'required|date',
                'date_end' => 'required|array|min:1',
                'date_end.*' => 'required|date|after_or_equal:date_start.*',
                'time_start' => 'required|array|min:1',
                'time_start.*' => 'required|date_format:H:i',
                'time_end' => 'required|array|min:1',
                'time_end.*' => 'required|date_format:H:i|after:time_start.*',
                'npersons' => 'required|integer',
                'focalPerson' => 'required|string|max:50',
                'tables' => 'nullable|integer',
                'chairs' => 'nullable|integer',
                'otherFacilities' => 'nullable|string|max:50',
                'conferenceRoom' => 'required|string|exists:conference_rooms,CRoomID',
                'requesterName' => 'required|string|max:50',
                'RequesterSignature' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:10240',
            ]);

            // Custom validation for duplicate dates
            $dates = $validated['date_start'];
            if (count($dates) !== count(array_unique($dates))) {
                throw ValidationException::withMessages(['date_start' => 'Duplicate dates are not allowed.']);
            }

            $office = Office::query()->where('OfficeID', $validated['officeName'])->firstOrFail();
            $conferenceRoom = ConferenceRoom::query()->where('CRoomID', $validated['conferenceRoom'])->firstOrFail();

            foreach ($validated['date_start'] as $index => $dateStart) {
                $generatedID = $this->generateUniqueID();
                $requesterSignaturePath = null;

                if ($request->hasFile('RequesterSignature')) {
                    $requesterSignaturePath = $request->file('RequesterSignature')->store('/uploads/signatures', 'public');
                }

                // Check for existing approved and ongoing forms
                $existingRequest = ConferenceRequest::query()
                    ->where('CRoomID', $conferenceRoom->CRoomID)
                    ->where('FormStatus', 'Approved')
                    ->where('EventStatus', 'Ongoing')
                    ->where('date_start', $dateStart)
                    ->where('time_start', '<=', $validated['time_end'][$index])
                    ->where('time_end', '>=', $validated['time_start'][$index])
                    ->exists();

                $availability = $existingRequest ? 'Unavailable' : 'Available';

                ConferenceRequest::create([
                    'CRequestID' => $generatedID,
                    'OfficeID' => $office->OfficeID,
                    'Purpose' => $validated['purpose'],
                    'npersons' => $validated['npersons'],
                    'focalPerson' => $validated['focalPerson'],
                    'tables' => $validated['tables'],
                    'chairs' => $validated['chairs'],
                    'otherFacilities' => $validated['otherFacilities'],
                    'CRoomID' => $conferenceRoom->CRoomID,
                    'FormStatus' => 'Pending',
                    'EventStatus' => '-',
                    'RequesterSignature' => $requesterSignaturePath,
                    'RequesterName' => $validated['requesterName'],
                    'date_start' => $dateStart,
                    'date_end' => $validated['date_end'][$index],
                    'time_start' => $validated['time_start'][$index],
                    'time_end' => $validated['time_end'][$index],
                    'Availability' => $availability,
                ]);
            }

            return redirect()->back()->with('success', 'Conference room request submitted successfully.');
        } catch (ValidationException $e) {
            Log::error('Validation errors: ', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            Log::error('Conference room request submission failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Form submission failed. Please try again.');
        }
    }

    public function updateCForm(Request $request): RedirectResponse
    {
        try {
            // Validate only the formStatus and eventStatus fields
            $validated = $request->validate([
                'CRequestID' => 'required|string|exists:conference_room_requests,CRequestID',
                'FormStatus' => 'required|string|in:Pending,Approved,Not Approved',
                'EventStatus' => 'required|string|in:-,Ongoing,Finished,Cancelled',
            ]);

            // Retrieve the conference request using Eloquent ORM
            $conferenceRequest = ConferenceRequest::with('conferenceRoom')->where('CRequestID', $validated['CRequestID'])->firstOrFail();

            // Update the formStatus and eventStatus fields
            $conferenceRequest->update([
                'FormStatus' => $validated['FormStatus'],
                'EventStatus' => $validated['EventStatus'],
            ]);

            return redirect()->back()->with('success', 'Conference room request updated successfully.');
        } catch (ValidationException $e) {
            Log::error('Validation errors: ', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            Log::error('Conference room request update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Form update failed. Please try again.');
        }
    }

    /**
     * Retrieves the request data for a specific conference request.
     *
     * This function fetches the conference request data along with related office and conference room details.
     * It then returns a view with the retrieved data.
     *
     * @param string $CRequestID The ID of the conference request to retrieve.
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application The view displaying the conference request details.
     */
    public function getRequestData(string $CRequestID): View|Factory|Application
    {
        $requestData = ConferenceRequest::with('office', 'conferenceRoom')->findOrFail($CRequestID);
        return view('ConferencedetailEdit', compact('requestData'));
    }

    public function getLogData(string $CRequestID): View|Factory|Application
    {
        $requestLogData = ConferenceRequest::with('office', 'conferenceRoom')->findOrFail($CRequestID);
        return view('ConferencelogDetail', compact('requestLogData'));
    }
    public function getCalendarEvents()
    {
        $events = ConferenceRequest::select('Purpose', 'date_start', 'date_end', 'time_start', 'time_end')
            ->get()
            ->map(function($event) {
                return [
                    'title' => $event->Purpose,
                    'start' => $event->date_start . 'T' . $event->time_start,
                    'end' => $event->date_end . 'T' . $event->time_end,
                ];
            });

        return response()->json($events);
    }

    // Conference Request Main Filter and Sort
    public function fetchSortedRequests(Request $request): \Illuminate\Http\JsonResponse
    {
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $conferenceRoom = $request->get('conference_room');
        $formStatuses = $request->get('form_statuses', ['Approved', 'Pending']);
        $eventStatuses = $request->get('event_statuses', ['Ongoing', '-']);

        Log::info('Filter parameters:', [
            'sort' => $sort,
            'order' => $order,
            'conference_room' => $conferenceRoom,
            'form_statuses' => $formStatuses,
            'event_statuses' => $eventStatuses,
        ]);

        $query = ConferenceRequest::with('office', 'conferenceRoom')
            ->orderBy($sort, $order);

        if ($conferenceRoom) {
            $query->whereHas('conferenceRoom', function ($q) use ($conferenceRoom) {
                $q->where('CRoomName', $conferenceRoom);
            });
        }

        if ($formStatuses) {
            $query->whereIn('FormStatus', $formStatuses);
        }

        if ($eventStatuses) {
            $query->whereIn('EventStatus', $eventStatuses);
        }

        $conferenceRequests = $query->get();

        Log::info('Query results:', $conferenceRequests->toArray());

        return response()->json($conferenceRequests);
    }

    // Conference Request Logs Filter and Sort
    public function fetchSortedLogRequests(Request $request): \Illuminate\Http\JsonResponse
    {
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $conferenceRoom = $request->get('conference_room');
        $statusPairs = $request->get('status_pairs', []);

        Log::info('Filter parameters:', [
            'sort' => $sort,
            'order' => $order,
            'conference_room' => $conferenceRoom,
            'status_pairs' => $statusPairs,
        ]);

        $query = ConferenceRequest::with('office', 'conferenceRoom')
            ->orderBy($sort, $order);

        if ($conferenceRoom) {
            $query->whereHas('conferenceRoom', function ($q) use ($conferenceRoom) {
                $q->where('CRoomName', $conferenceRoom);
            });
        }

        if ($statusPairs) {
            $query->where(function ($q) use ($statusPairs) {
                foreach ($statusPairs as $pair) {
                    [$formStatus, $eventStatus] = explode(',', $pair);
                    $q->orWhere(function ($q) use ($formStatus, $eventStatus) {
                        $q->where('FormStatus', $formStatus)
                            ->where('EventStatus', $eventStatus);
                    });
                }
            });
        }

        $conferenceRequests = $query->get();

        Log::info('Query results:', $conferenceRequests->toArray());

        return response()->json($conferenceRequests);
    }


// Availability

public function checkAvailability($conferenceRoomId, $dateStart, $timeStart, $dateEnd, $timeEnd, $createdAt)
{
    $startDateTime = Carbon::parse($dateStart . ' ' . $timeStart);
    $endDateTime = Carbon::parse($dateEnd . ' ' . $timeEnd);

    // Find all requests that overlap with the requested time range
    $overlappingRequests = ConferenceRequest::where('conference_room_id', $conferenceRoomId)
        ->where('FormStatus', 'Approved')
        ->where('EventStatus', 'Ongoing')
        ->where(function($query) use ($startDateTime, $endDateTime) {
            $query->where(function($q) use ($startDateTime, $endDateTime) {
                $q->where('date_start', '<=', $endDateTime->toDateString())
                  ->where('date_end', '>=', $startDateTime->toDateString())
                  ->where(function($q2) use ($startDateTime, $endDateTime) {
                      $q2->where('time_start', '<', $endDateTime->toTimeString())
                         ->where('time_end', '>', $startDateTime->toTimeString());
                  });
            });
        })
        ->orderBy('created_at', 'asc')
        ->get();

    // Check if the request overlaps with any existing booking
    if ($overlappingRequests->count() > 0) {
        $firstRequest = $overlappingRequests->first();

        // Compare the created_at timestamp to determine availability
        if ($firstRequest->created_at->eq($createdAt)) {
            return "Available";
        } else {
            // Find the next available time slot after the last overlapping request
            $nextAvailableSlot = ConferenceRequest::where('conference_room_id', $conferenceRoomId)
                ->where('FormStatus', 'Approved')
                ->where('EventStatus', 'Ongoing')
                ->where('date_start', '>', $endDateTime->toDateString())
                ->orderBy('date_start', 'asc')
                ->orderBy('time_start', 'asc')
                ->first();

            if ($nextAvailableSlot) {
                $nextAvailableTime = Carbon::parse($nextAvailableSlot->date_start . ' ' . $nextAvailableSlot->time_start);
                return "Not Available, next available at " . $nextAvailableTime->format('m-d-Y h:i A');
            } else {
                return "Not Available";
            }
        }
    } else {
        return "Available";
    }
}

// Add this method to ConferenceController.php
    public function updateAvailability(Request $request)
    {
        $conferenceRoomId = $request->input('conference_room_id');
        $dateStart = $request->input('date_start');
        $timeStart = $request->input('time_start');
        $dateEnd = $request->input('date_end');
        $timeEnd = $request->input('time_end');
        $createdAt = $request->input('created_at');

        $availability = $this->checkAvailability($conferenceRoomId, $dateStart, $timeStart, $dateEnd, $timeEnd, $createdAt);

        return response()->json(['availability' => $availability]);
    }


}
