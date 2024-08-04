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
use Throwable;

class ConferenceController extends Controller
{
    public function showForm(): View|Factory|Application
    {
        return view('components.conference-form');
    }

    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_10();
        } while (ConferenceRequest::query()->where('CRequestID', $generatedID)->exists());

        return $generatedID;
    }

    public function submitCForm(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'officeName' => 'required|string|exists:offices,OfficeID',
                'purpose' => 'required|string|max:255',
                'dateStart' => 'required|array|min:1',
                'dateStart.*' => 'required|date',
                'dateEnd' => 'required|array|min:1',
                'dateEnd.*' => 'required|date',
                'timeStart' => 'required|array|min:1',
                'timeStart.*' => 'required|date_format:H:i',
                'timeEnd' => 'required|array|min:1',
                'timeEnd.*' => 'required|date_format:H:i',
                'npersons' => 'required|integer',
                'focalPerson' => 'required|string|max:50',
                'tables' => 'nullable|integer',
                'chairs' => 'nullable|integer',
                'otherFacilities' => 'nullable|string|max:50',
                'conferenceRoom' => 'required|string|exists:conference_rooms,CRoomID',
                'requesterName' => 'required|string|max:50',
                'RequesterSignature' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:10240',
            ]);
    
            $generatedID = $this->generateUniqueID();
            $office = Office::query()->where('OfficeID', $validated['officeName'])->firstOrFail();
            $conferenceRoom = ConferenceRoom::query()->where('CRoomID', $validated['conferenceRoom'])->firstOrFail();
    
            $conferenceRequest = new ConferenceRequest();
            $conferenceRequest->fill([
                'CRequestID' => $generatedID,
                'OfficeID' => $office->OfficeID,
                'Purpose' => $validated['purpose'],
                'dateStart' => $validated['dateStart'],
                'dateEnd' => $validated['dateEnd'],
                'timeStart' => $validated['timeStart'],
                'timeEnd' => $validated['timeEnd'],
                'npersons' => $validated['npersons'],
                'focalPerson' => $validated['focalPerson'],
                'tables' => $validated['tables'],
                'chairs' => $validated['chairs'],
                'otherFacilities' => $validated['otherFacilities'],
                'CRoomID' => $conferenceRoom->CRoomID,
                'RequesterName' => $validated['requesterName'],
                'FormStatus' => 'Pending',
                'EventStatus' => '',
            ]);
    
            if ($request->hasFile('RequesterSignature')) {
                $filePath = $request->file('RequesterSignature')->store('/uploads/signatures', 'public');
                $conferenceRequest->RequesterSignature = $filePath;
            }
    
            $conferenceRequest->save();
    
            return redirect()->back()->with('success', 'Conference room request submitted successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            Log::error('Conference room request submission failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Form submission failed. Please try again.');
        }
    }
}
