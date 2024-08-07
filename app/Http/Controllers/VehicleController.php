<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Employee;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Models\VehicleRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

// ...


class VehicleController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_10();
        } while (VehicleRequest::query()->where('CRequestID', $generatedID)->exists());

        return $generatedID;
    }

    /**
     * @throws ValidationException
     */
    public function submitVForm(Request $request)
    {
        try {
            $validated = $request->validate([
                'RequestingOffice' => 'required|string|max:50',
                'Purpose' => 'required|string|max:50',
                'PassengerName' => 'required|array',
                'PassengerName.*' => 'array|max:50',
                'date_start.*' => 'required|date',
                'date_end' => 'required|array|min:1',
                'date_end.*' => 'required|date|after_or_equal:date_start.*',
                'time_start' => 'required|array|min:1',
                'time_start.*' => 'required|date_format:H:i',
                'Location' => 'required|string|max:50',
                'RequesterName' => 'required|string|max:50',
                'RequesterEmail' => 'required|email|max:50',
                'RequesterContact' => 'required|string|max:13',
                'ReceivedDate' => 'required|date',
                'RequesterSignature' => 'required|file|mimes:png,jpg,jpeg|max:32256', // example: 31.46MB in kilobytes
                'IPAddress' => 'required|ip',

                // To be filled by dispatcher
                'ReceivedBy' => 'required|string|max:50',50

            ]);

            // Custom validation for duplicate dates
            $dates = $validated['date_start'];
            if (count($dates) !== count(array_unique($dates))) {
                throw ValidationException::withMessages(['date_start' => 'Duplicate dates are not allowed.']);
            }

            $office = Office::query()->where('OfficeID', $validated['officeName'])->firstOrFail();

            $driver = Driver::query()->where('DriverID', $validated['DriverID'])->firstOrFail();
            $employee = Employee::query()->where('EmpID', $validated['EmployeeID'])->firstOrFail();

            foreach ($validated['date_start'] as $index => $dateStart) {
                $generatedID = $this->generateUniqueID();
                $requesterSignaturePath = null;

                if ($request->hasFile('RequesterSignature')) {
                    $requesterSignaturePath = $request->file('RequesterSignature')->store('/uploads/signatures', 'public');
                }

                VehicleRequest::create([
                    'VRequestID' => $generatedID,
                    'DriverID' => $driver->DriverID,
                    'VehicleID' => '', // null bc no assigned vehicle yet
                    'OfficeID' => $office->OfficeID,
                    'Purpose' => $validated['purpose'],
                    'EmployeeID' => $employee->EmpID, // need to be an array for multiselect
                    'date_start' => $dateStart,
                    'date_end' => $validated['date_end'][$index],
                    'time_start' => $validated['time_start'][$index],
                    'time_end' => $validated['time_end'][$index],
                    'FormStatus' => 'Pending',
                    'EventStatus' => '-',
                    'RequesterSignature' => $requesterSignaturePath,
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
}
