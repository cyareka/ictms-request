<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleRequest;

// ...


class VehicleController extends Controller
{
    public function create()
    {
        return view('vehicle-request.create');
    }

    public function submitVForm(Request $request)
    {
        $validated = $request->validate([
            'RequestingOffice' => 'required|string|max:50',
            'Purpose' => 'required|string|max:50',
            'PassengerName' => 'required|array',
            'PassengerName.*' => 'string|max:50',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'time_start' => 'required',
            'time_end' => 'required|after_or_equal:time_start',
            'Location' => 'required|string|max:50',
            'requested_by' => 'required|string|max:50',
            'RequesterEmail' => 'required|email|max:50',
            'contact_no' => 'required|string|max:13',
            'ReceivedDate' => 'required|date',
            // 'ip_address' => 'required|ip',
            'RequesterSignature' => 'required|file|mimes:png,jpg,jpeg|max:32256', // example: 31.46MB in kilobytes
            // 'received_by' => 'required|string|max:50',
        ]);

        // $vehicleController = new VehicleController();
        // $vehicleController->create($validated);

        return redirect()->route('vehicle-request.create')->with('success', 'Form submitted successfully!');
    }
}
