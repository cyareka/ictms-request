<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Helpers\IDGenerator;
use Throwable;

class NDriverController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_8();
        } while (Driver::query()->where('DriverID', $generatedID)->exists());

        return $generatedID;
    }

    public function store(Request $request)
    {
        $request->validate([
            'DriverName' => 'required|string|max:50',
            'DriverEmail' => 'required|email|max:30',
            'ContactNo' => 'required|string|max:13',
        ]);

        // Remove leading zero from ContactNo
        $contactNo = preg_replace('/^0/', '', $request->ContactNo);

        // Add +63 to the beginning of ContactNo
        $contactNo = '+63' . $contactNo;

        // Convert DriverName to uppercase at the start of each word
        $driverName = ucwords(strtolower($request->DriverName));

        $generatedID = $this->generateUniqueID();

        Driver::create([
            'DriverID' => $generatedID,
            'DriverName' => $driverName,
            'DriverEmail' => $request->DriverEmail,
            'ContactNo' => $contactNo,
            'status' => 1,
        ]);

        return redirect()->back()->with('success', 'Driver added successfully!');
    }

    public function toggleStatus($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->status = !$driver->status;
        $driver->save();

        return redirect()->back()->with('success', 'Driver status updated successfully!');
    }
}
