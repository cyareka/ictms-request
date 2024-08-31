<?php

namespace App\Http\Controllers;

use App\Models\FocalPerson;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Helpers\IDGenerator;
use Illuminate\Support\Facades\Log;
use Exception;

class NFocalPController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_3();
        } while (FocalPerson::query()->where('FocalPID', $generatedID)->exists());

        return $generatedID;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'FPName' => 'required|string|max:50',
                'officeName' => 'required|string|exists:offices,OfficeID',
            ]);

            $generatedID = $this->generateUniqueID();
            $office = Office::query()->where('OfficeID', $validated['officeName'])->firstOrFail();

            FocalPerson::create([
                'FocalPID' => $generatedID,
                'FPName' => $validated['FPName'],
                'OfficeID' => $office->OfficeID,
            ]);

            return redirect()->back()->with('success', 'Focal Person added successfully!');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error adding employee: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add employee.');
        }
    }
}
