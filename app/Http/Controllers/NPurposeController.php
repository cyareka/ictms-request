<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurposeRequest;
use App\Helpers\IDGenerator;

class NPurposeController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_3();
        } while (PurposeRequest::query()->where('PurposeID', $generatedID)->exists());

        return $generatedID;
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $purposeRequests = PurposeRequest::all();
        return view('NPurpose', compact('purposeRequests'));
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $purposeRequest = PurposeRequest::findOrFail($id);
        $purposeRequest->delete();

        return redirect()->route('purpose.requests.index')->with('success', 'Purpose request deleted successfully.');
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $purposeRequest = PurposeRequest::findOrFail($id);
        return view('editPurposeRequest', compact('purposeRequest'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'request_p' => 'required|string',
            'purpose'   => 'required|string',
        ]);

        $purposeRequest = PurposeRequest::findOrFail($id);
        $purposeRequest->update($validatedData);

        return redirect()->route('purpose.requests.index')->with('success', 'Purpose request updated successfully.');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
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
