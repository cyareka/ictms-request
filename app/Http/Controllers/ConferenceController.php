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
//                    'Availability' => 'Available',
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

    public function fetchCalendarEvents(Request $request): \Illuminate\Http\JsonResponse
{
    $title = $request->get('Purpose');
    $conferenceRoom = $request->get('conference_room');
    $formStatuses = $request->get('form_statuses', []);
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');

    $query = ConferenceRequest::with('conferenceRoom');

    if ($title) {
        $query->where('Purpose', 'like', "%$title%");
    }

    if ($conferenceRoom) {
        $query->whereHas('conferenceRoom', function ($q) use ($conferenceRoom) {
            $q->where('CRoomName', $conferenceRoom);
        });
    }

    if ($formStatuses) {
        $query->whereIn('FormStatus', $formStatuses);
    }

    if ($startDate && $endDate) {
        $query->whereBetween('date_start', [$startDate, $endDate])
              ->whereBetween('date_end', [$startDate, $endDate]);
    }

    $conferenceRequests = $query->get()
        ->map(function ($event) {
            return [
                'title' => $event->Purpose,
                'conferenceRoom' => $event->conferenceRoom ? $event->conferenceRoom->CRoomName : 'N/A',
                'start' => $event->date_start . 'T' . $event->time_start,
                'end' => $event->date_end . 'T' . $event->time_end,
                'EventStatus' => $event->FormStatus,
            ];
        });

    return response()->json($conferenceRequests);
}

    // Conference Request Main Filter and Sort
    public function fetchSortedRequests(Request $request): \Illuminate\Http\JsonResponse
    {
        // Get sorting criteria from the request
        $sortBy = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
    
        // Fetch requests based on filters and sorting criteria
        $conferenceRequests = ConferenceRequest::with(['conferenceRoom', 'office']) // Include related models
            ->whereIn('FormStatus', ['Approved', 'Pending'])
            ->whereIn('EventStatus', ['Ongoing', '-'])
            ->orderBy($sortBy, $order)
            ->get()
            ->map(function ($request) {
                // Determine availability for each request
                $availability = $request->FormStatus == 'Approved' ? 'Available' : $this->checkAvailability(
                    $request->CRoomID,
                    $request->date_start,
                    $request->time_start,
                    $request->date_end,
                    $request->time_end,
                    $request->CRequestID
                );
    
                return array_merge($request->toArray(), ['availability' => $availability]);
            });
    
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

    public function checkAvailability($CRoomID, $dateStart, $timeStart, $dateEnd, $timeEnd, $CRequestID): string
    {
        // Check for overlapping approved requests
        $overlappingApproved = ConferenceRequest::where('CRoomID', $CRoomID)
            ->where('FormStatus', 'Approved')
            ->where('EventStatus', 'Ongoing')
            ->where('CRequestID', '!=', $CRequestID)
            ->where(function ($query) use ($dateStart, $timeStart, $dateEnd, $timeEnd) {
                $query->where(function ($q) use ($dateStart, $timeStart, $dateEnd, $timeEnd) {
                    $q->where('date_start', '<=', $dateEnd)
                        ->where('date_end', '>=', $dateStart)
                        ->where('time_start', '<=', $timeEnd)
                        ->where('time_end', '>=', $timeStart);
                });
            })
            ->exists();

        if ($overlappingApproved) {
            return 'Available';
        } else
            return 'Not Available';
    }
}
