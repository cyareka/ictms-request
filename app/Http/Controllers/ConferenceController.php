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

    // TODO: Reference array as script to display as header('error' / 'success')
    public function submitCForm(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'controlNo' => 'required|string|max:10',
            'date' => 'required|date',
            'officeName' => 'required|string|max:50',
            'purpose' => 'required|string|max:255',
            'dateStart' => 'required|date',
            'dateEnd' => 'required|date',
            'timeStart' => 'required|date_format:h:i a',
            'timeEnd' => 'required|date_format:h:i a',
            'persons' => 'required|integer',
            'focalPerson' => 'required|string|max:50',
            'tables' => 'nullable|integer',
            'chairs' => 'nullable|integer',
            'otherFacilities' => 'nullable|string|max:50',
            'conferenceRoom' => 'required|string',
            'requesterName' => 'required|string|max:50',
            'RequesterSignature' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:31456',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validation failed. Please check your input.');
        }

        try {
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

            session()->flash('success', 'Form submitted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Form submission failed. Please try again.');
        }

        return redirect()->back()->with('success', 'Conference room request submitted successfully.');
    }
}
