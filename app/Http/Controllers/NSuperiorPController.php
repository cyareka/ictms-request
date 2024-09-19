<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Superior;
use App\Helpers\IDGenerator;
use Throwable;

class NSuperiorPController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_3();
        } while (Superior::query()->where('SuperiorID', $generatedID)->exists());

        return $generatedID;
    }

    public function store(Request $request)
    {
        $request->validate([
            'SName' => 'required|string|max:50',
            'Designation' => 'required|string|max:30',
        ]);

        $Sname = ucwords(strtolower($request->SName));

        $generatedID = $this->generateUniqueID();

        // Check if there is already an active superior
        $activeSuperior = Superior::where('status', 1)->first();
        $status = $activeSuperior ? 0 : 1;

        Superior::create([
            'SuperiorID' => $generatedID,
            'SName' => $Sname,
            'Designation' => $request->Designation,
            'status' => $status,
        ]);

        return redirect()->back()->with('success', 'Superior added successfully!');
    }

    public function toggleStatus($id)
    {
        $superior = Superior::findOrFail($id);

        if ($superior->status == 0) {
            // Check if there is already an active superior
            $activeSuperior = Superior::where('status', 1)->first();
            if ($activeSuperior) {
                return redirect()->back()->with('error', 'Only one superior can be active at a time.');
            }
        }

        // Toggle the status
        $superior->status = !$superior->status;
        $superior->save();

        return redirect()->back()->with('success', 'Superior status updated successfully.');
    }
}
