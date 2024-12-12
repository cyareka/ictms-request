<?php

namespace App\Http\Controllers;

use App\Models\AAuthority;
use App\Helpers\IDGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        try {
            $authorities = AAuthority::all();
        } catch (Throwable $e) {
            Log::error('Failed to retrieve authorities: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to retrieve authorities. Please try again.');
        }
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
        try {
            $activeAuthority = AAuthority::where('status', 1)->first();
        } catch (Throwable $e) {
            Log::error('Failed to check active authority: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to check active authority. Please try again.');
        }
        
        $status = $activeAuthority ? 0 : 1;

        try {
            AAuthority::create([
                'AAID' => $generatedID,
                'AAName' => $AAName,
                'AAPosition' => $request->AAPosition,
                'status' => $status,
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to add Authority: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add Authority. Please try again.');
        }

        return redirect()->back()->with('success', 'Authority added successfully.');
    }

    public function toggleStatus($id)
    {
        try {
            $authority = AAuthority::findOrFail($id);
        } catch (Throwable $e) {
            Log::error('Failed to find Authority: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to find Authority. Please try again.');
        }

        if ($authority->status == 0) {
            // Ensure only one active authority
            try {
                $activeAuthority = AAuthority::where('status', 1)->first();
            } catch (Throwable $e) {
                Log::error('Failed to check active authority: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to check active authority. Please try again.');
            }
            
            if ($activeAuthority) {
                return redirect()->back()->with('error', 'Only one authority can be active at a time.');
            }
        }

        // Toggle the status
        try {
            $authority->status = !$authority->status;
            $authority->save();
        } catch (Throwable $e) {
            Log::error('Failed to update status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update status. Please try again.');
        }

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $authority = AAuthority::findOrFail($id);
        } catch (Throwable $e) {
            Log::error('Failed to find Authority: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to find Authority. Please try again.');
        }

        try {
            $authority->delete();
        } catch (Throwable $e) {
            Log::error('Failed to delete Authority: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete Authority. Please try again.');
        }

        return redirect()->back()->with('success', 'Authority deleted successfully.');
    }
}
