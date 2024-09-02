<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Helpers\IDGenerator;
use Throwable;

class NVehicleController extends Controller
{
    private function generateUniqueID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_3();
        } while (Vehicle::query()->where('VehicleID', $generatedID)->exists());

        return $generatedID;
    }

    public function store(Request $request)
    {
        $request->validate([
            'VehicleType' => 'required|string|max:50',
            'PlateNo' => 'required|string|max:8',
            'Capacity' => 'required|integer|min:0',
        ]);

        $vehicleType = ucwords(strtolower($request->VehicleType));
        $generatedID = $this->generateUniqueID();

        Vehicle::create([
            'VehicleID' => $generatedID,
            'VehicleType' => $vehicleType,
            'PlateNo' => $request->PlateNo,
            'Capacity' => $request->Capacity,

        ]);

        return redirect()->back()->with('success', 'Vehicle added successfully!');
    }
}

