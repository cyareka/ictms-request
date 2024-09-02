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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Throwable;
use function Pest\Laravel\get;

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
            $generatedID = $idGenerator->generateID_CR();
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
        Log::info('Incoming request data:', $request->all());

        try {
            $validated = $request->validate([
                'officeName' => 'required|string|exists:offices,OfficeID',
                'purposeSelect' => 'nullable|string|exists:purpose_requests,PurposeID',
                'purposeInput' => 'nullable|string|max:50',
                'date_start.*' => 'required|date_format:Y-m-d',
                'date_end' => 'required|array|min:1',
                'date_end.*' => 'required|date_format:Y-m-d|after_or_equal:date_start.*',
                'time_start' => 'required|array|min:1',
                'time_start.*' => 'required|date_format:H:i',
                'time_end' => 'required|array|min:1',
                'time_end.*' => 'required|date_format:H:i|after:time_start.*',
                'npersons' => 'required|integer',
                'focalPersonSelect' => 'nullable|string|exists:focal_person,FocalPID',
                'focalPersonInput' => 'nullable|string|max:50',
                'tables' => 'nullable|integer',
                'chairs' => 'nullable|integer',
                'otherFacilities' => 'nullable|string|max:50',
                'conferenceRoom' => 'required|string|exists:conference_rooms,CRoomID',
                'requesterName' => 'required|string|max:50',
                'RequesterSignature' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:10240',
            ]);

            Log::info('Validated data:', $validated);

            $purpose = $validated['purposeInput'] ?? null;
            $focalPerson = $validated['focalPersonInput'] ?? null;
            $otherFacilities = $validated['otherFacilities'] ?? null;
            // $otherFacilities = $validated['otherFacilitiesInput'] ?: $validated['otherFacilitiesSelect'];

            $dates = $validated['date_start'];
            if (count($dates) !== count(array_unique($dates))) {
                throw ValidationException::withMessages(['date_start' => 'Duplicate dates are not allowed.']);
            }

            $office = Office::query()->where('OfficeID', $validated['officeName'])->firstOrFail();
            $conferenceRoom = ConferenceRoom::query()->where('CRoomID', $validated['conferenceRoom'])->firstOrFail();
            // $purposes = PurposeRequest::query()->where('PurposeID', $validated['purposeSelect'])->firstOrFail();
            // $focal = FocalPerson::query()->where('FocalPID', $validated['focalPersonSelect'])->firstOrFail();

            foreach ($validated['date_start'] as $index => $dateStart) {
                Log::info('Processing date start:', ['date_start' => $dateStart, 'index' => $index]);

                $generatedID = $this->generateUniqueID();
                $requesterSignaturePath = null;

                if ($request->hasFile('RequesterSignature')) {
                    $requesterSignaturePath = $request->file('RequesterSignature')->store('/uploads/signatures', 'public');
                }

                $existingRequests = ConferenceRequest::query()
                    ->where('CRoomID', $conferenceRoom->CRoomID)
                    ->where('FormStatus', 'Approved')
                    ->get();

                $availability = true;

                foreach ($existingRequests as $existingRequest) {
                    if (
                        $validated['date_start'][$index] <= $existingRequest->date_end &&
                        $validated['date_end'][$index] >= $existingRequest->date_start &&
                        $validated['time_start'][$index] <= $existingRequest->time_end &&
                        $validated['time_end'][$index] >= $existingRequest->time_start
                    ) {
                        $availability = false;
                        break;
                    }
                }

    /*            Log::info('Creating conference request:', [
                    'CRequestID' => $generatedID,
                    'OfficeID' => $office->OfficeID,
                    'PurposeID' => $purpose,
                    'npersons' => $validated['npersons'],
                    'focalPerson' => $focalPerson,
                    'CAvailability' => $availability,
                    'tables' => $validated['tables'],
                    'chairs' => $validated['chairs'],
                    'otherFacilities' => $otherFacilities,
                    'CRoomID' => $conferenceRoom->CRoomID,
                    'FormStatus' => 'Pending',
                    'EventStatus' => '-',
                    'RequesterSignature' => $requesterSignaturePath,
                    'RequesterName' => $validated['requesterName'],
                    'date_start' => $dateStart,
                    'date_end' => $validated['date_end'][$index],
                    'time_start' => $validated['time_start'][$index],
                    'time_end' => $validated['time_end'][$index],
                ]);*/

                ConferenceRequest::create([
                    'CRequestID' => $generatedID,
                    'OfficeID' => $office->OfficeID,
                    'PurposeID' => $validated['purposeSelect']?? null,
                    'PurposeOthers' => $purpose,
                    'npersons' => $validated['npersons'],
                    'FocalPID' => $validated['focalPersonSelect'] ?? null,
                    'FPOthers'  => $focalPerson,
                    'CAvailability' => $availability,
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
                ]);
            }

            return redirect()->back()->with('success', 'Conference room request submitted successfully.');
        } catch (ValidationException $e) {
            Log::error('Validation errors:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            Log::error('Conference room request submission failed:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Form submission failed. Please try again.');
        }
    }

    /**
     * Approves a conference room request.
     *
     * This function updates the FormStatus and EventStatus of a conference request to 'Approved' and 'Ongoing', respectively.
     * It logs the approval action and redirects back with a success message.
     *
     * @param string $CRequestID The ID of the conference request to approve.
     * @return \Illuminate\Http\RedirectResponse The response object redirecting back to the conference request list with a success message.
     */
    public function updateCForm(Request $request): RedirectResponse
    {
        try {
            Log::info('Request Data:', $request->all());
            // Validate only the formStatus and eventStatus fields
            $validated = $request->validate([
                'CRequestID' => 'required|string|exists:conference_room_requests,CRequestID',
                'certfile-upload' => 'required|file|mimes:pdf',
                'FormStatus' => 'required|string|in:Pending,For Approval,Approved,Not Approved',
                'EventStatus' => 'required|string|in:-,Ongoing,Finished,Cancelled',
            ]);

            if ($request->hasFile('certfile-upload')) {
                $file = $request->file('certfile-upload')->store('uploads/confe_request/files', 'public');
                $validated['certfile-upload'] = $file;
            }

            // Retrieve the conference request using Eloquent ORM
            $conferenceRequest = ConferenceRequest::with('conferenceRoom')->where('CRequestID', $validated['CRequestID'])->firstOrFail();

            // Update the formStatus and eventStatus fields
            $conferenceRequest->update([
                'FormStatus' => $validated['FormStatus'],
                'EventStatus' => $validated['EventStatus'],
                'certfile-upload' => $validated['certfile-upload'],
            ]);

            // If the request is approved, update availability and other pending requests
            if ($validated['FormStatus'] === 'Approved' && $validated['EventStatus'] === 'Ongoing') {
                $conferenceRequest->CAvailability = true;
                $conferenceRequest->save();

                $otherRequests = ConferenceRequest::all()
                    ->where('CRoomID', '=', $conferenceRequest->CRoomID)
                    ->where('CRequestID', '!=', $conferenceRequest->CRequestID)
                    ->where('FormStatus', '=', 'Pending')
                    ->where('EventStatus', '=', '-');

                foreach ($otherRequests as $otherRequest) {
                    if (
                        ($otherRequest->date_start <= $conferenceRequest->date_end && $otherRequest->date_end >= $conferenceRequest->date_start) &&
                        ($otherRequest->time_start <= $conferenceRequest->time_end && $otherRequest->time_end >= $conferenceRequest->time_start)
                    ) {
                        $otherRequest->update(['CAvailability' => false]);
                    }
                }
            } elseif (($validated['FormStatus'] === 'Pending' && $validated['EventStatus'] === '-') || ($validated['FormStatus'] === 'For Approval' && $validated['EventStatus'] === '-')) {
                $conferenceRequest->CAvailability = true;
                $conferenceRequest->save();

                $otherRequests = ConferenceRequest::all()
                    ->where('CRoomID', '=', $conferenceRequest->CRoomID)
                    ->where('CRequestID', '!=', $conferenceRequest->CRequestID)
                    ->where('FormStatus', '=', 'Pending')
                    ->where('EventStatus', '=', '-');

                foreach ($otherRequests as $otherRequest) {
                    if (
                        ($otherRequest->date_start <= $conferenceRequest->date_end && $otherRequest->date_end >= $conferenceRequest->date_start) &&
                        ($otherRequest->time_start <= $conferenceRequest->time_end && $otherRequest->time_end >= $conferenceRequest->time_start)
                    ) {
                        $otherRequest->update(['CAvailability' => true]);
                    }
                }
            } elseif (($validated['FormStatus'] === 'Not Approved' && $validated['EventStatus'] === '-') || ($validated['FormStatus'] === 'Approved' && $validated['EventStatus'] === 'Finished') || (($validated['FormStatus'] === 'Approved') && $validated['EventStatus'] === 'Cancelled')) {
                $conferenceRequest->CAvailability = null;
                $conferenceRequest->save();

                $otherRequests = ConferenceRequest::all()
                    ->where('CRoomID', '=', $conferenceRequest->CRoomID)
                    ->where('CRequestID', '!=', $conferenceRequest->CRequestID)
                    ->where('FormStatus', '=', 'Pending')
                    ->where('EventStatus', '=', '-');

                foreach ($otherRequests as $otherRequest) {
                    if (
                        ($otherRequest->date_start <= $conferenceRequest->date_end && $otherRequest->date_end >= $conferenceRequest->date_start) &&
                        ($otherRequest->time_start <= $conferenceRequest->time_end && $otherRequest->time_end >= $conferenceRequest->time_start)
                    ) {
                        $otherRequest->update(['CAvailability' => true]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Conference room request updated successfully.');
        } catch (ValidationException $e) {
            Log::error('Validation errors: ', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            Log::error('Conference room request update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Form update failed. Please try again.');
        }
    }

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

        // Exclude specific FormStatus and EventStatus combinations
        $query->where(function ($q) {
            $q->whereNot(function ($q) {
                $q->where('FormStatus', 'Not Approved')
                    ->where('EventStatus', '-');
            })
                ->whereNot(function ($q) {
                    $q->where('FormStatus', 'Approved')
                        ->where('EventStatus', 'Cancelled');
                })
                ->whereNot(function ($q) {
                    $q->where('FormStatus', 'Approved')
                        ->where('EventStatus', 'Finished');
                });
        });

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
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $conferenceRoom = $request->input('conference_room');
        $formStatuses = $request->input('form_statuses', ['Approved', 'Pending', 'For Approval']);
        $eventStatuses = $request->input('event_statuses', ['Ongoing', '-']);
        $perPage = $request->input('per_page', 5);
        $search = $request->input('search', '');

        $query = ConferenceRequest::query()->with('office', 'conferenceRoom')
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

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('CRequestID', 'like', '%' . $search . '%')
                    ->orWhereHas('conferenceRoom', function ($q) use ($search) {
                        $q->where('CRoomName', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('office', function ($q) use ($search) {
                        $q->where('OfficeName', 'like', '%' . $search . '%');
                    })
                    ->orWhere('date_start', 'like', '%' . $search . '%')
                    ->orWhere('time_start', 'like', '%' . $search . '%')
                    ->orWhere('EventStatus', 'like', '%' . $search . '%')
                    ->orWhere('FormStatus', 'like', '%' . $search . '%');

            });
        }

        $results = $query->paginate($perPage);

        \Log::info('Query results:', $results->toArray());

        return response()->json([
            'data' => $results->items(),
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
            ],
        ]);
    }

    // Conference Request Logs Filter and Sort
    public function fetchSortedLogRequests(Request $request): \Illuminate\Http\JsonResponse
    {
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $conferenceRoom = $request->get('conference_room');
        $statusPairs = $request->get('status_pairs', []);

        // Define allowed status pairs
        $allowedStatusPairs = [
            'Approved,Finished',
            'Approved,Cancelled',
            'Not Approved,-'
        ];

        // Filter status pairs to include only allowed ones
        $filteredStatusPairs = array_filter($statusPairs, function ($pair) use ($allowedStatusPairs) {
            return in_array($pair, $allowedStatusPairs);
        });

        Log::info('Filter parameters:', [
            'sort' => $sort,
            'order' => $order,
            'conference_room' => $conferenceRoom,
            'status_pairs' => $filteredStatusPairs,
        ]);

        $query = ConferenceRequest::with('office', 'conferenceRoom')
            ->orderBy($sort, $order);

        if ($conferenceRoom) {
            $query->whereHas('conferenceRoom', function ($q) use ($conferenceRoom) {
                $q->where('CRoomName', $conferenceRoom);
            });
        }

        if ($filteredStatusPairs) {
            $query->where(function ($q) use ($filteredStatusPairs) {
                foreach ($filteredStatusPairs as $pair) {
                    [$formStatus, $eventStatus] = explode(',', $pair);
                    $q->orWhere(function ($q) use ($formStatus, $eventStatus) {
                        $q->where('FormStatus', $formStatus)
                            ->where('EventStatus', $eventStatus);
                    });
                }
            });
        } else {
            // Ensure only allowed status pairs are included if no status pairs are provided
            $query->where(function ($q) use ($allowedStatusPairs) {
                foreach ($allowedStatusPairs as $pair) {
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

    public function fetchStatistics(): \Illuminate\Http\JsonResponse
    {
        $statistics = [
            'pendingRequests' => ConferenceRequest::where('FormStatus', 'Pending')->count(),
            'dailyRequests' => ConferenceRequest::whereDate('created_at', now()->toDateString())->count(),
            'monthlyRequests' => ConferenceRequest::whereMonth('created_at', now()->month)->count(),
            'requestsPerOffice' => ConferenceRequest::select('OfficeID', \DB::raw('count(*) as total'))
                ->groupBy('OfficeID')
                ->with('office')
                ->get()
                ->map(function ($item) {
                    return [
                        'office' => $item->office->OfficeName,
                        'total' => $item->total,
                    ];
                }),
        ];

        return response()->json($statistics);
    }

    public function getConferenceRoomUsage()
    {
        // Fetch monthly usage data for MAGITING
        $magitingUsage = DB::table('conference_rooms')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->where('CRoomName', 'Magiting')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        // Fetch monthly usage data for MAAGAP
        $maagapUsage = DB::table('conference_rooms')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->where('CRoomName', 'Maagap')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        return response()->json([
            'magitingUsage' => $magitingUsage,
            'maagapUsage' => $maagapUsage
        ]);
    }
}
