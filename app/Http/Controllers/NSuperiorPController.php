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

        Superior::create([
            'SuperiorID' => $generatedID,
            'SName' => $Sname,
            'Designation' => $request->Designation,
            
        ]);

        return redirect()->back()->with('success', 'Superior added successfully!');
    }
}