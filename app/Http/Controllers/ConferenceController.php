<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\ConferenceRequest;
use Throwable;

class ConferenceController extends Controller
{
    public function showForm(): View|Factory|Application
    {
        return view('components.conference-form');
    }

public function submitCForm(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'controlNo' => 'required|string|max:10',
        'date' => 'required|date',
        'officeName' => 'required|string|max:50',
        'purpose' => 'required|string|max:255',
        'dateStart' => 'required|array|min:1',
        'dateStart.*' => 'required|date',
        'dateEnd' => 'required|array|min:1',
        'dateEnd.*' => 'required|date',
        'timeStart' => 'required|array|min:1',
        'timeStart.*' => 'required|date_format:H:i',
        'timeEnd' => 'required|array|min:1',
        'timeEnd.*' => 'required|date_format:H:i',
        'persons' => 'required|integer',
        'focalPerson' => 'required|string|max:50',
        'tables' => 'nullable|integer',
        'chairs' => 'nullable|integer',
        'otherFacilities' => 'nullable|string|max:50',
        'conferenceRoom' => 'required|string',
        'requesterName' => 'required|string|max:50',
        'RequesterSignature' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:31456',
    ]);

    try {
        $conferenceRequest = new ConferenceRequest();
        $conferenceRequest->fill([
            'CRequestID' => $validated['controlNo'],
            'ReceivedDate' => $validated['date'],
            'RequestingOffice' => $validated['officeName'],
            'Purpose' => $validated['purpose'],
            'date_start' => $validated['dateStart'],
            'date_end' => $validated['dateEnd'],
            'time_start' => $validated['timeStart'],
            'time_end' => $validated['timeEnd'],
            'npersons' => $validated['persons'],
            'focalPerson' => $validated['focalPerson'],
            'tables' => $validated['tables'],
            'chairs' => $validated['chairs'],
            'otherFacilities' => $validated['otherFacilities'],
            'CRoomName' => $validated['conferenceRoom'],
            'RequesterName' => $validated['requesterName'],
        ]);

        if ($request->hasFile('RequesterSignature')) {
            $filePath = $request->file('RequesterSignature')->store('signatures', 'public');
            $conferenceRequest->RequesterSignature = $filePath;
        }

        $conferenceRequest->save();

        return redirect()->back()->with('success', 'Conference room request submitted successfully.');
    } catch (Throwable) {
        return redirect()->back()->with('error', 'Form submission failed. Please try again.');
    }
}
}
