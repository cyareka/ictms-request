<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurposeRequest;
use App\Helpers\IDGenerator;

class PurposeController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_3();
        } while (PurposeRequest::query()->where('PurposeID', $generatedID)->exists());

        return $generatedID;
    }

    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'request_p' => 'required|string',
            'purpose'   => 'required|string',
        ]);

        // Generate a unique ID
        $generatedID = $this->generateUniqueID();

        // Create a new PurposeRequest record
        PurposeRequest::create([
            'PurposeID' => $generatedID,
            'request_p' => $validatedData['request_p'],
            'purpose'   => $validatedData['purpose'],
        ]);

        // Redirect or respond with a success message
        return redirect()->back()->with('success', 'Successfully Added a Purpose');
    }
}