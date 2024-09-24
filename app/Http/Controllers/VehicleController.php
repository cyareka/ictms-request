<?php

namespace App\Http\Controllers;

use App\Helpers\IDGenerator;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Office;
use App\Models\PurposeRequest;
use App\Models\Vehicle;
use App\Models\VRequestPassenger;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\VehicleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class VehicleController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_VR();
        } while (VehicleRequest::query()->where('VRequestID', $generatedID)->exists());

        return $generatedID;
    }

    private function generateUniqueVRPassID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_10(); // Assuming the same length of ID
        } while (DB::table('vrequest_passenger')->where('VRPassID', $generatedID)->exists());

        return $generatedID;
    }

    private function generatePurposeID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_3();
        } while (PurposeRequest::query()->where('PurposeID', $generatedID)->exists());

        return $generatedID;
    }

    private function insertPurposeInput(array $validated): void
    {
        if (!empty($validated['purposeInput'])) {
            $similarPurpose = DB::table('purpose_requests')
                ->where('purpose', 'like', '%' . $validated['purposeInput'] . '%')
                ->where('request_p', 'Vehicle')
                ->exists();

            if ($similarPurpose) {
                session()->flash('purposeInputError', 'A similar purpose name already exists.');
                throw ValidationException::withMessages(['purposeInput' => 'A similar purpose name already exists.']);
            }

            $capitalizedPurpose = ucwords($validated['purposeInput']);

            DB::table('purpose_requests')->insert([
                'PurposeID' => $this->generatePurposeID(),
                'request_p' => 'Vehicle',
                'purpose' => $capitalizedPurpose,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }


    /**
     * @throws ValidationException
     */
    public function submitVForm(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'officeName' => 'required|string|exists:offices,OfficeID',
                'purposeSelect' => 'nullable|string|max:255|required_without:purposeInput|exists:purpose_requests,PurposeID',
                'purposeInput' => 'nullable|string|max:50',
                'passengers' => 'required|array',
                'passengers.*' => 'string|exists:employees,EmployeeID',
                'date_start.*' => 'required|date',
                'date_end' => 'required|array|min:1',
                'date_end.*' => 'required|date|after_or_equal:date_start.*',
                'time_start' => 'required|array|min:1',
                'time_start.*' => 'required|date_format:H:i',
                'Destination' => 'required|string|max:50',
                'RequesterName' => 'required|string|max:50',
                'RequesterEmail' => 'required|email|max:50',
                'RequesterContact' => 'required|string|max:13',
                'RequesterSignature' => 'required|file|mimes:png,jpg,jpeg|max:32256',
            ]);
            if (empty($validated['purposeSelect'])) {
                $this->insertPurposeInput($validated);
            }

// Capitalize the first letter of specific fields
            $purpose = !empty($validated['purposeInput']) ? ucwords($validated['purposeInput']) : null;
            $validated['Destination'] = ucwords($validated['Destination']);
            $validated['RequesterName'] = ucwords($validated['RequesterName']);

            // Format the contact number
            $contactNo = preg_replace('/^0/', '', $validated['RequesterContact']);
            $validated['RequesterContact'] = '+63' . $contactNo;

            $passengers = $validated['passengers'];
            if (count($passengers) !== count(array_unique($passengers))) {
                throw ValidationException::withMessages(['passengers' => 'Duplicate passengers are not allowed.']);
            }

            $dates = $validated['date_start'];
            if (count($dates) !== count(array_unique($dates))) {
                throw ValidationException::withMessages(['date_start' => 'Duplicate dates are not allowed.']);
            }

            $office = Office::query()->where('OfficeID', $validated['officeName'])->firstOrFail();

            foreach ($validated['date_start'] as $index => $dateStart) {
                $generatedID = $this->generateUniqueID();
                $requesterSignaturePath = null;

                if ($request->hasFile('RequesterSignature')) {
                    $requesterSignaturePath = $request->file('RequesterSignature')->store('/uploads/signatures', 'public');
                }

                VehicleRequest::create([
                    'VRequestID' => $generatedID,
                    'OfficeID' => $office->OfficeID,
                    'PurposeID' => $validated['purposeSelect'] ?? null,
                    'PurposeOthers' => $purpose,
                    'date_start' => $dateStart,
                    'date_end' => $validated['date_end'][$index],
                    'time_start' => $validated['time_start'][$index],
                    'Destination' => $validated['Destination'],
                    'RequesterName' => $validated['RequesterName'],
                    'RequesterContact' => $validated['RequesterContact'],
                    'RequesterEmail' => $validated['RequesterEmail'],
                    'RequesterSignature' => $requesterSignaturePath,
                    'IPAddress' => $request->ip(),
                    'DriverID' => null,
                    'VehicleID' => null,
                    'ReceivedBy' => null,
                    'Remarks' => null,
                    'AAID' => null,
                    'SOID' => null,
                    'ASignatory' => null,
                    'certfile-upload' => null,
                    'FormStatus' => 'Pending',
                    'EventStatus' => '-',
                ]);
                Log::debug('Vehicle request created for date start:', ['dateStart' => $dateStart]);
            }

            foreach ($passengers as $passenger) {
                $VRPassID = $this->generateUniqueVRPassID();
                Log::debug('Generated unique VRPassID:', ['VRPassID' => $VRPassID]);

                DB::table('vrequest_passenger')->insert([
                    'VRPassID' => $VRPassID,
                    'VRequestID' => $generatedID,
                    'EmployeeID' => $passenger,
                ]);
                Log::debug('Passenger inserted:', ['passenger' => $passenger]);
            }

            return redirect()->back()->with('success', 'Vehicle request submitted successfully.');
        } catch (ValidationException $e) {
            Log::error('Validation failed in submitVForm:', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            $errorMessage = 'Form submission failed. Please try again.';
            $detailedErrorMessage = $e->getMessage();

            Log::error('An unexpected error occurred in submitVForm:', [
                'message' => $detailedErrorMessage,
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            return redirect()->back()->with('error', $errorMessage . ' Error details: ' . $detailedErrorMessage);
        }
    }

    public function updateVForm(Request $request, $VRequestID): RedirectResponse
    {
        try {
            // Log the incoming request data
            Log::info('Request Data:', $request->all());

            // Fetch the existing vehicle request
            $vehicleRequest = VehicleRequest::findOrFail($VRequestID);

            // Validate the incoming request data
            $validated = $request->validate([
                'DriverID' => 'nullable|string|exists:driver,DriverID',
                'VehicleID' => 'nullable|string|exists:vehicle,VehicleID',
                'ReceivedBy' => 'nullable|string|max:50',
                'remark' => 'nullable|string|max:255',
                'AAID' => 'nullable|string|exists:a_authorities,AAID',
                'SOID' => 'nullable|string|exists:so_authorities,SOID',
                'ASignatory' => 'nullable|string|max:50',  // Allow string initially
                'certfile-upload' => 'nullable|file|mimes:pdf',
                'FormStatus' => 'nullable|string|in:Pending,For Approval,Approved,Not Approved',
                'EventStatus' => 'nullable|string|in:-,Ongoing,Finished,Cancelled',
            ]);

            Log::info('Request Data:', $request->all());

            $validated['driver'] = $request->input('DriverID');
            $validated['VName'] = $request->input('VehicleID');
            $validated['AAID'] = $request->input('AAuth');
            $validated['SOID'] = $request->input('SOAuth');
            $validated['remark'] = $request->input('Remarks');

            // Convert the signatory name to an ID
            if (!empty($validated['ASignatory']) || !empty($validated['ReceivedBy'])) {
                $userId = DB::table('users')->where('name', $validated['ASignatory'] ?? $validated['ReceivedBy'])->value('id');
                if (!$userId) {
                    Log::error('User name does not exist:', ['User' => $validated['ASignatory'] ?? $validated['ReceivedBy']]);
                    return redirect()->back()->withErrors(['User' => 'The selected user is invalid.'])->withInput();
                }
                $validated['ASignatory'] = $userId;
                $validated['ReceivedBy'] = $userId;
            }

            if ($request->hasFile('certfile-upload')) {
                $VRequestID = $request->input('VRequestID');
                $ReceivedBy = $request->input('ReceivedBy');
                $FormStatus = $request->input('FormStatus');
                $EventStatus = $request->input('EventStatus');
                $currentDate = now()->format('mdY');

                $newFileName = "{$VRequestID}_{$ReceivedBy}_{$currentDate}_{$FormStatus}_{$EventStatus}." . $request->file('certfile-upload')->getClientOriginalExtension();
                $file = $request->file('certfile-upload')->storeAs('uploads/vehicle_request/files', $newFileName, 'public');
                $validated['certfile-upload'] = $file;
            }

            $vehicleRequest->update($validated);

            /*            if ($validated['FormStatus'] === 'Approved' && $validated['EventStatus'] === 'Ongoing') {
                            $vehicleRequest->VAvailability = true;
                            $vehicleRequest->save();

                            $otherRequests = VehicleRequest::where('VehicleID', $vehicleRequest->VehicleID)
                                ->where('VRequestID', '!=', $vehicleRequest->VRequestID)
                                ->where('FormStatus', 'Pending')
                                ->where('EventStatus', '-')
                                ->get();

                            foreach ($otherRequests as $otherRequest) {
                                if (
                                    ($otherRequest->date_start <= $vehicleRequest->date_end && $otherRequest->date_end >= $vehicleRequest->date_start) &&
                                    ($otherRequest->time_start <= $vehicleRequest->time_end && $otherRequest->time_end >= $vehicleRequest->time_start)
                                ) {
                                    $otherRequest->update([
                                        'FormStatus' => 'Not Approved',
                                        'EventStatus' => '-',
                                        'VAvailability' => null
                                    ]);
                                }
                            }
                        } elseif (($validated['FormStatus'] === 'Pending' && $validated['EventStatus'] === '-') || ($validated['FormStatus'] === 'For Approval' && $validated['EventStatus'] === '-')) {
                            $vehicleRequest->VAvailability = null;
                            $vehicleRequest->save();

                            $otherRequests = VehicleRequest::where('VehicleID', $vehicleRequest->VehicleID)
                                ->where('VRequestID', '!=', $vehicleRequest->VRequestID)
                                ->where('FormStatus', 'Pending')
                                ->where('EventStatus', '-')
                                ->get();

                            foreach ($otherRequests as $otherRequest) {
                                if (
                                    ($otherRequest->date_start <= $vehicleRequest->date_end && $otherRequest->date_end >= $vehicleRequest->date_start) &&
                                    ($otherRequest->time_start <= $vehicleRequest->time_end && $otherRequest->time_end >= $vehicleRequest->time_start)
                                ) {
                                    $otherRequest->update(['VAvailability' => true]);
                                }
                            }
                        } elseif (($validated['FormStatus'] === 'Not Approved' && $validated['EventStatus'] === '-') || ($validated['FormStatus'] === 'Approved' && $validated['EventStatus'] === 'Finished') || (($validated['FormStatus'] === 'Approved') && $validated['EventStatus'] === 'Cancelled')) {
                            $vehicleRequest->VAvailability = null;
                            $vehicleRequest->save();

                            $otherRequests = VehicleRequest::where('VehicleID', $vehicleRequest->VehicleID)
                                ->where('VRequestID', '!=', $vehicleRequest->VRequestID)
                                ->where('FormStatus', 'Pending')
                                ->where('EventStatus', '-')
                                ->get();

                            foreach ($otherRequests as $otherRequest) {
                                if (
                                    ($otherRequest->date_start <= $vehicleRequest->date_end && $otherRequest->date_end >= $vehicleRequest->date_start) &&
                                    ($otherRequest->time_start <= $vehicleRequest->time_end && $otherRequest->time_end >= $vehicleRequest->time_start)
                                ) {
                                    $otherRequest->update([
                                        'FormStatus' => 'Pending',
                                        'EventStatus' => '-',
                                        'VAvailability' => true
                                    ]);
                                }
                            }
                        }*/

            return redirect()->route('VehicledetailEdit', ['VRequestID' => $VRequestID])->with('success', 'Vehicle request updated successfully.');
        } catch (ValidationException $e) {
            Log::error('Validation failed in updateVForm:', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Update failed. Please try again.');
        }
    }

    public function fetchSortedVRequests(Request $request): \Illuminate\Http\JsonResponse
    {
        // Retrieve request parameters with default values
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $formStatuses = $request->get('form_statuses', ['Approved', 'Pending', 'For Approval']);
        $eventStatuses = $request->get('event_statuses', ['Ongoing', '-']);
        $searchQuery = $request->get('search_query', '');
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        Log::info('Filter parameters:', [
            'sort' => $sort,
            'order' => $order,
            'form_statuses' => $formStatuses,
            'event_statuses' => $eventStatuses,
            'search_query' => $searchQuery,
        ]);

        // Define month name-to-numeric mapping
        $monthNames = [
            'january' => '01', 'february' => '02', 'march' => '03', 'april' => '04',
            'may' => '05', 'june' => '06', 'july' => '07', 'august' => '08',
            'september' => '09', 'october' => '10', 'november' => '11', 'december' => '12'
        ];

        try {
            $query = VehicleRequest::with('office');

            // Apply search query filters
            if ($searchQuery) {
                $query->where(function ($q) use ($searchQuery, $monthNames) {
                    // Check if the search query is a month name
                    $lowerSearchQuery = strtolower($searchQuery);
                    if (isset($monthNames[$lowerSearchQuery])) {
                        // Search in the created_at column by the numeric month
                        $numericMonth = $monthNames[$lowerSearchQuery];
                        $q->whereMonth('created_at', $numericMonth);
                    } else {
                        // Standard search query in other fields
                        $q->where('VRequestID', 'like', '%' . $searchQuery . '%')
                            ->orWhere('Destination', 'like', '%' . $searchQuery . '%')
                            ->orWhere('PurposeID', 'like', '%' . $searchQuery . '%')
                            ->orWhere('PurposeOthers', 'like', '%' . $searchQuery . '%')
                            ->orWhereHas('office', function ($q) use ($searchQuery) {
                                $q->where('OfficeName', 'like', '%' . $searchQuery . '%');
                            })
                            ->orWhere('FormStatus', 'like', '%' . $searchQuery . '%');
                    }
                });
            }

            // Apply form status filter
            if ($formStatuses) {
                $query->whereIn('FormStatus', $formStatuses);
            }

            // Apply event status filter
            if ($eventStatuses) {
                $query->whereIn('EventStatus', $eventStatuses);
            }

            // Apply sorting and pagination
            $vehicleRequests = $query->orderBy($sort, $order)->paginate($perPage, ['*'], 'page', $page);

            Log::info('Query results:', $vehicleRequests->toArray());

            return response()->json([
                'data' => $vehicleRequests->items(),
                'pagination' => [
                    'current_page' => $vehicleRequests->currentPage(),
                    'last_page' => $vehicleRequests->lastPage(),
                    'per_page' => $vehicleRequests->perPage(),
                    'total' => $vehicleRequests->total(),
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('An error occurred while fetching sorted vehicle requests:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Failed to fetch vehicle requests.'], 500);
        }
    }

    public function fetchCalendarEvents(Request $request): \Illuminate\Http\JsonResponse
{
    $title = $request->get('Purpose');
    $destination = $request->get('Destination');
    $formStatuses = $request->get('form_statuses', []);
    $startDate = $request->get('date_start');
    $endDate = $request->get('date_end');

    try {
        $query = VehicleRequest::query();

        if ($title) {
            // Check if the title is a PurposeID
            $purposeName = DB::table('purpose_requests')
                ->where('PurposeID', $title)
                ->value('purpose'); // Assume 'purpose' is the column for the purpose name

            if ($purposeName) {
                // Title is a PurposeID, so filter by PurposeID and join with purpose_requests to get the name
                $query->where('PurposeID', $title);
            } else {
                // Title is not a PurposeID, so check if it's in PurposeOthers
                $query->where(function ($q) use ($title) {
                    $q->where('PurposeOthers', 'like', "%$title%");
                });
            }
        }

        if ($destination) {
            $query->where('Destination', 'like', "%$destination%");
        }

        if ($formStatuses) {
            $query->whereIn('FormStatus', $formStatuses);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('date_start', [$startDate, $endDate])
                ->orWhereBetween('date_end', [$startDate, $endDate])
                ->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('date_start', '<=', $startDate)
                        ->where('date_end', '>=', $endDate);
                });
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

        $vehicleRequests = $query->get()
            ->map(function ($event) {
                // Fetch the purpose name based on PurposeID
                $purposeName = $event->PurposeID ? DB::table('purpose_requests')->where('PurposeID', $event->PurposeID)->value('purpose') : null;

                return [
                    'title' => $purposeName ?? $event->PurposeOthers ?? 'N/A',
                    'start' => $event->date_start . 'T' . $event->time_start,
                    'end' => $event->date_end . 'T' . $event->time_end,
                    'EventStatus' => $event->FormStatus,
                    'Destination' => $event->Destination,
                    'VRequestID' => $event->VRequestID, // Include VRequestID in the response
                    'FormStatus' => $event->FormStatus // Include FormStatus in the response
                ];
            });

        return response()->json($vehicleRequests);
    } catch (Throwable $e) {
        Log::error('An error occurred while fetching calendar events:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => 'Failed to fetch calendar events.'], 500);
    }
}

// In `app/Http/Controllers/VehicleController.php`

// In `app/Http/Controllers/VehicleController.php`

    private function getPassengersByRequestId($VRequestID)
    {
        try {
            // Fetch passengers associated with the given VRequestID
            $passengers = VRequestPassenger::where('VRequestID', $VRequestID)
                ->join('employees', 'vrequest_passenger.EmployeeID', '=', 'employees.EmployeeID')
                ->select('employees.EmployeeID', 'employees.EmployeeName')
                ->get();

            Log::info('Passengers fetched:', $passengers->toArray());

            return $passengers;
        } catch (Throwable $e) {
            Log::error('An error occurred while fetching passengers:', [
                'VRequestID' => $VRequestID,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Failed to fetch passengers.'], 500);
        }
    }

    public function getRequestData($VRequestID): View|Factory|Application
    {
        try {
            // Fetch the vehicle request data
            $requestData = VehicleRequest::with('office')->findOrFail($VRequestID);
            $passengers = $this->getPassengersByRequestId($VRequestID);

            // Pass the request data and passengers to the view
            return view('VehicledetailEdit', ['requestData' => $requestData, 'passengers' => $passengers]);
        } catch (Throwable $e) {
            Log::error('An error occurred while fetching request data:', [
                'VRequestID' => $VRequestID,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return view('VehicledetailEdit')->with('error', 'Failed to fetch request data.');
        }
    }
    // Vehicle Stats
    public function fetchVStatistics(): \Illuminate\Http\JsonResponse
    {
        $statistics = [
            'pendingRequests' => VehicleRequest::where('FormStatus', 'Pending')->count(),
            'dailyRequests' => VehicleRequest::whereDate('created_at', now()->toDateString())->count(),
            'monthlyRequests' => VehicleRequest::whereMonth('created_at', now()->month)->count(),
            'requestsPerOffice' => VehicleRequest::select('OfficeID', DB::raw('count(*) as total'))
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
    //Start Wlay pulos
    public function getVLogData($VRequestID): View|Factory|Application
    {
        // Initialize variables with default values
        $requestLogData = null;
        $passengers = collect(); // Empty collection
        $error = null;

        try {
            // Fetch the vehicle request data
            $requestLogData = VehicleRequest::with('office')->findOrFail($VRequestID);
            $passengers = $this->getPassengersByRequestId($VRequestID);
        } catch (Throwable $e) {
            Log::error('An error occurred while fetching request data:', [
                'VRequestID' => $VRequestID,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $error = 'Failed to fetch request data.';
        }
        return view('vehiclelogDetail', ['requestLogData' => $requestLogData, 'passengers' => $passengers]);
    }
    //end sa wlay pulos
    public function fetchSortedVLogRequests(Request $request): \Illuminate\Http\JsonResponse
{
    // Fetch parameters from the request with default values
    $searchQuery = $request->input('search_query', '');
    $order = $request->input('order', 'desc');
    $sort = $request->input('sort', 'created_at');
    $page = $request->input('page', 1);
    $perPage = $request->input('per_page', 5);

    // Get filters
    $VehicleRequests = $request->input('vehicle_request');
    $statusPairs = $request->input('status_pairs', []);

    // Define allowed status pairs for filtering
    $allowedStatusPairs = [
        'Approved,Finished',
        'Approved,Cancelled',
        'Not Approved,-'
    ];

    // Ensure that only allowed status pairs are used
    $filteredStatusPairs = array_filter($statusPairs, function ($pair) use ($allowedStatusPairs) {
        return in_array($pair, $allowedStatusPairs);
    });

    // Define month names to numeric conversion
    $monthNames = [
        'january' => '01', 'february' => '02', 'march' => '03', 'april' => '04',
        'may' => '05', 'june' => '06', 'july' => '07', 'august' => '08',
        'september' => '09', 'october' => '10', 'november' => '11', 'december' => '12'
    ];

    // Query from VehicleRequest model
    $query = VehicleRequest::query();

    // Apply search query
    if (!empty($searchQuery)) {
        $query->where(function ($q) use ($searchQuery, $monthNames) {
            $lowerSearchQuery = strtolower($searchQuery);

            if (isset($monthNames[$lowerSearchQuery])) {
                // Search by month name in the created_at column
                $q->whereMonth('created_at', $monthNames[$lowerSearchQuery]);
            } else {
                // Search by other fields
                $q->where('VRequestID', 'like', '%' . $searchQuery . '%')
                    ->orWhere('Destination', 'like', '%' . $searchQuery . '%')
                    ->orWhere('PurposeID', 'like', '%' . $searchQuery . '%')
                    ->orWhereHas('office', function ($q) use ($searchQuery) {
                        $q->where('OfficeName', 'like', '%' . $searchQuery . '%');
                    })
                    ->orWhere('FormStatus', 'like', '%' . $searchQuery . '%')
                    ->orWhere('EventStatus', 'like', '%' . $searchQuery . '%');
            }
        });
    }

    // Apply allowed status pair filtering
    if (!empty($filteredStatusPairs)) {
        $query->where(function ($q) use ($filteredStatusPairs) {
            foreach ($filteredStatusPairs as $pair) {
                list($formStatus, $eventStatus) = explode(',', $pair);
                $q->orWhere(function ($q) use ($formStatus, $eventStatus) {
                    $q->where('FormStatus', $formStatus)
                        ->where('EventStatus', $eventStatus);
                });
            }
        });
    } else {
        // If no status pairs are provided, default to the allowed ones
        $query->where(function ($q) use ($allowedStatusPairs) {
            foreach ($allowedStatusPairs as $pair) {
                list($formStatus, $eventStatus) = explode(',', $pair);
                $q->orWhere(function ($q) use ($formStatus, $eventStatus) {
                    $q->where('FormStatus', $formStatus)
                        ->where('EventStatus', $eventStatus);
                });
            }
        });
    }

    // Sort the results
    $requests = $query->orderBy($sort, $order)->paginate($perPage, ['*'], 'page', $page);

    // Return paginated and filtered data
    return response()->json([
        'data' => $requests->items(), // Paginated results
        'pagination' => [
            'current_page' => $requests->currentPage(),
            'last_page' => $requests->lastPage(),
            'total' => $requests->total(),
        ]
    ]);
}

    public function getVehicleUsage(): \Illuminate\Http\JsonResponse
    {
        // Fetch vehicle types and their usage counts
        $vehicleUsage = \DB::table('vehicle_request')
            ->leftJoin('vehicle', 'vehicle_request.VehicleID', '=', 'vehicle.VehicleID') // Use LEFT JOIN
            ->select('vehicle.VehicleType', \DB::raw('COUNT(vehicle_request.VehicleID) as count'))
            ->groupBy('vehicle.VehicleType')
            ->get();

        // Prepare data for the chart
        $dataPoints = [];
        $totalCount = $vehicleUsage->sum('count');

        if ($totalCount > 0) {
            foreach ($vehicleUsage as $usage) {
                // Check if VehicleType is null, and use a fallback label
                if ($usage->VehicleType !== null) { // Exclude unknown vehicle types
                    $percentage = ($usage->count / $totalCount) * 100;
                    $dataPoints[] = ['y' => round($percentage, 2), 'label' => $usage->VehicleType];
                }
            }
        } else {
            // Optionally, provide a fallback if no data is available
            $dataPoints[] = ['y' => 100, 'label' => 'No Data Available'];
        }
        // Log dataPoints for debugging
        Log::info('dataPoints:', $dataPoints);

        // Return dataPoints as JSON
        return response()->json(['dataPoints' => $dataPoints]);
    }
}
