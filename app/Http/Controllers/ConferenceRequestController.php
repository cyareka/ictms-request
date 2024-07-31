<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConferenceRequest;

class ConferenceController extends Controller
{
    public function showForm()
    {
        return view('components.conference-form');
    }

    public function submitCForm(Request $request)
    {
        $request->validateCForm($request);

        $conferenceRequest = new ConferenceRequest();
        $conferenceRequest->CRequestID = $request->controlNo;
        $conferenceRequest->ReceivedDate = $request->date;
        $conferenceRequest->RequestingOffice = $request->officeName;
        $conferenceRequest->Purpose = $request->purpose;
        $conferenceRequest->date_start = $request->dateStart;
        $conferenceRequest->date_end = $request->dateEnd;
        $conferenceRequest->time_start = $request->timeStart;
        $conferenceRequest->time_end = $request->timeEnd;
        $conferenceRequest->npersons = $request->persons;
        $conferenceRequest->focalPerson = $request->focalPerson;
        $conferenceRequest->tables = $request->tables;
        $conferenceRequest->chairs = $request->chairs;
        $conferenceRequest->otherFacilities = $request->otherFacilities;
        $conferenceRequest->CRoomName = $request->conferenceRoom;
        $conferenceRequest->RequesterName = $request->requesterName;

        if ($request->hasFile('RequesterSignature')) {
            $filePath = $request->file('RequesterSignature')->store('signatures', 'public');
            $conferenceRequest->RequesterSignature = $filePath;
        }

        $conferenceRequest->save();

        return redirect()->route('conference.showForm')->with('success', 'Form submitted successfully!');
    }

    public function validateCForm(Request $request)
    {
        return $request->validate([
            'CRequestID' => 'required|string|max:10',
            'ReceivedDate' => 'required|date',
            'RequestingOffice' => 'required|string|max:50',
            'Purpose' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i',
            'npersons' => 'required|integer',
            'focalPerson' => 'required|string|max:50',
            'tables' => 'nullable|integer',
            'chairs' => 'nullable|integer',
            'otherFacilities' => 'nullable|string|max:50',
            'CRoomName' => 'required|string',
            'RequesterName' => 'required|string|max:50',
            'RequesterSignature' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:31456',
        ]);
    }
}
