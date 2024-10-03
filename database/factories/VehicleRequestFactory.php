<?php

namespace Database\Factories;

use App\Models\VehicleRequest;
use App\Models\Office;
use App\Models\PurposeRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\IDGenerator;

/**
 * @extends Factory<VehicleRequest>
 */
class VehicleRequestFactory extends Factory
{
    protected $model = VehicleRequest::class;

    /**
     * Generate a unique VRequestID.
     *
     * @return string
     */
    public function generateUniqueVRequestID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_10();
        } while (VehicleRequest::query()->where('VRequestID', $generatedID)->exists());

        return $generatedID;
    }

    public function definition(): array
    {
        $generatedID = $this->generateUniqueVRequestID();
        $officeID = Office::query()->inRandomOrder()->value('OfficeID');
        $purposeID = PurposeRequest::where('request_p', 'Vehicle')->inRandomOrder()->value('PurposeID');

        return [
            'VRequestID' => $generatedID,
            'OfficeID' => $officeID,
            'PurposeID' => $purposeID,
            'PurposeOthers' => $this->faker->optional()->sentence,
            'date_start' => $this->faker->date,
            'date_end' => $this->faker->date,
            'time_start' => $this->faker->time,
            'Destination' => $this->faker->city,
            'RequesterName' => $this->faker->name,
            'RequesterContact' => $this->faker->phoneNumber,
            'RequesterEmail' => $this->faker->safeEmail,
            'RequesterSignature' => 'data:image/png;base64,' . base64_encode(file_get_contents($this->faker->image())),
            'IPAddress' => $this->faker->ipv4,
            'DriverID' => null,
            'VehicleID' => null,
            'ReceivedBy' => null,
            'Remarks' => $this->faker->sentence,
            'AAID' => null,
            'SOID' => null,
            'ASignatory' => null,
            'certfile-upload' => null,
            'FormStatus' => 'Pending',
            'EventStatus' => '-',
        ];
    }
}