<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConferenceRoom;
use App\Helpers\IDGenerator;
use Throwable;

class NConferenceRController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_10();
        } while (ConferenceRoom::query()->where('CRoomID', $generatedID)->exists());

        return $generatedID;
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'CRoomName' => 'required|string|max:30',
            'Location' => 'required|string|max:30',
            'Capacity' => 'required|integer|min:1|not_in:0',
        ], [
            'CRoomName.required' => 'The conference room name is required.',
            'CRoomName.max' => 'The conference room name must not exceed 30 characters.',
            'Location.required' => 'The location is required.',
            'Location.max' => 'The location must not exceed 30 characters.',
            'Capacity.required' => 'The capacity is required.',
            'Capacity.integer' => 'The capacity must be an integer.',
            'Capacity.min' => 'The capacity must be at least 1.',
            'Capacity.not_in' => 'The capacity cannot be zero.',
        ]);

        try {
            // Convert CRoomName and Location to uppercase per word
            $CRoomName = ucwords(strtolower($request->CRoomName));
            $Location = ucwords(strtolower($request->Location));

            $generatedID = $this->generateUniqueID();  // Generate the unique ID

            $conferenceRoom = ConferenceRoom::create([
                'CRoomID' => $generatedID,
                'CRoomName' => $CRoomName,
                'Location' => $Location,
                'Capacity' => $request->Capacity,
            ]);

            return redirect()->back()->with('success', 'Conference details saved successfully!');
        } catch (Throwable $e) {
            // Handle the exception and return an error message
            return redirect()->back()->with('error', 'An error occurred while saving conference details. Please try again.');
        }
    }
}
