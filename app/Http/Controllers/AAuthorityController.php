<?php

namespace App\Http\Controllers;

use App\Models\AAuthority;
use App\Helpers\IDGenerator;
use Illuminate\Http\Request;
use Throwable;

class AAuthorityController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_3();
        } while (AAuthority::query()->where('AAID', $generatedID)->exists());

        return $generatedID;
    }

    public function index()
    {
        $authorities = AAuthority::all();
        return view('authority.index', compact('authorities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'AAName' => 'required|string|max:50',
            'AAPosition' => 'required|string|max:50',
        ]);

        $AAName = ucwords(strtolower($request->AAName));

        $generatedID = $this->generateUniqueID();

        // Check if there is already an active authority
        $activeAuthority = AAuthority::where('status', 1)->first();
        $status = $activeAuthority ? 0 : 1;

        try {
            AAuthority::create([
                'AAID' => $generatedID,
                'AAName' => $AAName,
                'AAPosition' => $request->AAPosition,
                'status' => $status,
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Failed to add Authority. Please try again.');
        }

        return redirect()->back()->with('success', 'Authority added successfully.');
    }

    public function toggleStatus($id)
    {
        $authority = AAuthority::findOrFail($id);

        if ($authority->status == 0) {
            // Ensure only one active authority
            $activeAuthority = AAuthority::where('status', 1)->first();
            if ($activeAuthority) {
                return redirect()->back()->with('error', 'Only one authority can be active at a time.');
            }
        }

        // Toggle the status
        $authority->status = !$authority->status;
        $authority->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function destroy($id)
    {
        $authority = AAuthority::findOrFail($id);

        try {
            $authority->delete();
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Failed to delete Authority. Please try again.');
        }

        return redirect()->back()->with('success', 'Authority deleted successfully.');
    }
}
