<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use App\Helpers\IDGenerator;
use Throwable;

class NOfficeController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_10();
        } while (Office::query()->where('OfficeID', $generatedID)->exists());

        return $generatedID;
    }

    public function store(Request $request)
    {
        $request->validate([
            'OfficeName' => 'required|string|max:50',
            'OfficeLocation' => 'required|string|max:30',
        ]);

        // Convert OfficeName to uppercase at the start of each word
        $OfficeName = ucwords(strtolower($request->OfficeName));

        $generatedID = $this->generateUniqueID();

        Office::create([
            'OfficeID' => $generatedID,
            'OfficeName' => $request-> OfficeName,
            'OfficeLocation' => $request->OfficeLocation,
        ]);

        return redirect()->back()->with('success', 'Office added successfully!');
    }
}
