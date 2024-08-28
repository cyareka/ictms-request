<?php

namespace App\Http\Controllers;

use App\Helpers\IDGenerator;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Office;
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
            $generatedID = $idGenerator->generateID_10();
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

    /**
     * @throws ValidationException
     */
    public function submitVForm(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'officeName' => 'required|string|exists:offices,OfficeID',
                'Purpose' => 'required|string|max:50',
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

            $passengers = $validated['passengers'];
            if (count($passengers) !== count(array_unique($passengers))) {
                throw ValidationException::withMessages(['passengers' => 'Duplicate passengers are not allowed.']);
            }

            // Custom validation for duplicate dates
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
                    'Purpose' => $validated['Purpose'],
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
            }

            foreach ($passengers as $passenger) {
                $VRPassID = $this->generateUniqueVRPassID();
                DB::table('vrequest_passenger')->insert([
                    'VRPassID' => $VRPassID,
                    'VRequestID' => $generatedID,
                    'EmployeeID' => $passenger,
                ]);
            }

            return redirect()->back()->with('success', 'Vehicle request submitted successfully.');
        } catch (ValidationException $e) {
            Log::error('Validation failed in submitVForm:', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            Log::error('An unexpected error occurred in submitVForm:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Form submission failed. Please try again.');
        }
    }

    public function fetchSortedVRequests(Request $request): \Illuminate\Http\JsonResponse
    {
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $formStatuses = $request->get('form_statuses', ['Approved', 'Pending']);
        $eventStatuses = $request->get('event_statuses', ['Ongoing', '-']);

        Log::info('Filter parameters:', [
            'sort' => $sort,
            'order' => $order,
            'form_statuses' => $formStatuses,
            'event_statuses' => $eventStatuses,
        ]);

        try {
            $query = VehicleRequest::with('office')
                ->orderBy($sort, $order);

            if ($formStatuses) {
                $query->whereIn('FormStatus', $formStatuses);
            }

            if ($eventStatuses) {
                $query->whereIn('EventStatus', $eventStatuses);
            }

            $vehicleRequests = $query->get();

            Log::info('Query results:', $vehicleRequests->toArray());

            return response()->json($vehicleRequests);
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
                $query->where('Purpose', 'like', "%$title%");
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
                    return [
                        'title' => $event->Purpose,
                        'start' => $event->date_start . 'T' . $event->time_start,
                        'end' => $event->date_end . 'T' . $event->time_end,
                        'EventStatus' => $event->FormStatus,
                        'Destination' => $event->Destination,
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

    public function getPassengersByRequestId($VRequestID)
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
                'Remarks' => 'nullable|string|max:255',
                'Availability' => 'nullable|string|max:50',
                'AAID' => 'nullable|string|exists:a_authorities,AAID',
                'SOID' => 'nullable|string|exists:so_authorities,SOID',
                'ASignatory' => 'nullable|string|max:50',  // Allow string initially
                'certfile-upload' => 'nullable|file|mimes:pdf',
                'FormStatus' => 'nullable|string|in:Pending,For Approval,Approved,Not Approved',
                'EventStatus' => 'nullable|string|in:-,Ongoing,Finished,Cancelled',
            ]);

            if ($request->hasFile('certfile-upload')) {
                $file = $request->file('certfile-upload')->store('uploads/vehicle_request/files', 'public');
                $validated['certfile-upload'] = $file;
            }

            // Map the input values to validated data
            $validated['DriverID'] = $request->input('driver'); // Ensure 'driver' is the select field name
            $validated['VehicleID'] = $request->input('VName'); // Ensure 'VName' is the select field name
            $validated['DriverID'] = $request->input('DriverID'); // Ensure 'driver' is the select field name
            $validated['VehicleID'] = $request->input('VehicleID'); // Ensure 'VName' is the select field name
            $validated['AAID'] = $request->input('AAuth'); // Ensure 'AAuth' is the correct input name
            $validated['SOID'] = $request->input('SOName');

            // Convert the signatory name to an ID
            if (!empty($validated['ASignatory'])) {
                $signatoryId = DB::table('users')->where('name', $validated['ASignatory'])->value('id');
                if (!$signatoryId) {
                    Log::error('ASignatory name does not exist:', ['ASignatory' => $validated['ASignatory']]);
                    return redirect()->back()->withErrors(['ASignatory' => 'The selected signatory is invalid.'])->withInput();
                }
                $validated['ASignatory'] = $signatoryId;
            }

            if (!empty($validated['ReceivedBy'])) {
                $receivedBy = DB::table('users')->where('name', $validated['ReceivedBy'])->value('id');
                if (!$receivedBy) {
                    Log::error('ReceivedBy name does not exist:', ['ReceivedBy' => $validated['ReceivedBy']]);
                    return redirect()->back()->withErrors(['ReceivedBy' => 'The selected received by is invalid.'])->withInput();
                }
                $validated['ReceivedBy'] = $receivedBy;
            }


            $vehicleRequest->update($validated);

            return redirect()->back()->with('success', 'Vehicle request updated successfully.');
        } catch (ValidationException $e) {
            Log::error('Validation failed in updateVForm:', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            Log::error('An unexpected error occurred in updateVForm:', [
                'VRequestID' => $VRequestID,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Update failed. Please try again.');
        }
    }

    public function fetchStatistics(): \Illuminate\Http\JsonResponse
    {
        $statistics = [
            'pendingRequests' => VehicleRequest::where('FormStatus', 'Pending')->count(),
            'dailyRequests' => VehicleRequest::whereDate('created_at', now()->toDateString())->count(),
            'monthlyRequests' => VehicleRequest::whereMonth('created_at', now()->month)->count(),
            'requestsPerOffice' => VehicleRequest::select('OfficeID', \DB::raw('count(*) as total'))
                ->groupBy('OfficeID')
                ->with('office')
                ->get()
                ->map(function ($item) {
                    return [
                        'office' => $item->office->name,
                        'total' => $item->total,
                    ];
                }),
        ];

        return response()->json($statistics);
    }
}



