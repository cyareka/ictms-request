<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SOAuthority;
use App\Helpers\IDGenerator;
use Throwable;

class SOAuthorityController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_3();
        } while (SOAuthority::query()->where('SOID', $generatedID)->exists());

        return $generatedID;
    }

    public function store(Request $request)
    {
        $request->validate([
            'SOName' => 'required|string|max:50',
            'SOPosition' => 'required|string|max:50',
        ]);

        $SOName = ucwords(strtolower($request->SOName));

        $generatedID = $this->generateUniqueID();

        // Check if there is already an active SO Authority
        $activeAuthority = SOAuthority::where('status', 1)->first();
        $status = $activeAuthority ? 0 : 1;

        try {
            SOAuthority::create([
                'SOID' => $generatedID,
                'SOName' => $SOName,
                'SOPosition' => $request->SOPosition,
                'status' => $status,
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Failed to add SO Authority. Please try again.');
        }

        return redirect()->back()->with('success', 'SO Authority added successfully!');
    }

    public function toggleStatus($id)
    {
        $authority = SOAuthority::findOrFail($id);

        if ($authority->status == 0) {
            // Check if there is already an active SO Authority
            $activeAuthority = SOAuthority::where('status', 1)->first();
            if ($activeAuthority) {
                return redirect()->back()->with('error', 'Only one SO Authority can be active at a time.');
            }
        }

        // Toggle the status
        $authority->status = !$authority->status;
        $authority->save();

        return redirect()->back()->with('success', 'SO Authority status updated successfully.');
    }
}
