<?php

namespace Database\Factories;

use App\Models\ConferenceRequest;
use App\Models\Office;
use App\Models\PurposeRequest;
use App\Models\ConferenceRoom;
use App\Models\FocalPerson;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\IDGenerator;

class ConferenceRequestFactory extends Factory
{
    protected $model = ConferenceRequest::class;

    /**
     * Generate a unique CRequestID.
     *
     * @return string
     */
    public function generateUniqueCRequestID(): string
    {
        $idGenerator = new IDGenerator();
        do {
            $generatedID = $idGenerator->generateID_CR();
        } while (ConferenceRequest::query()->where('CRequestID', $generatedID)->exists());

        return $generatedID;
    }

    public function definition(): array
    {
        $generatedID = $this->generateUniqueCRequestID();
        $officeID = Office::query()->inRandomOrder()->value('OfficeID');
        $purposeID = PurposeRequest::where('request_p', 'Conference Room')->inRandomOrder()->value('PurposeID');
        $conferenceRoomID = ConferenceRoom::query()->inRandomOrder()->value('CRoomID');
        $focalPersonID = FocalPerson::query()->inRandomOrder()->value('FocalPID');

        return [
            'CRequestID' => $generatedID,
            'OfficeID' => $officeID,
            'PurposeID' => $purposeID,
            'PurposeOthers' => $this->faker->optional()->sentence,
            'date_start' => $this->faker->date('Y-m-d'),
            'date_end' => $this->faker->date('Y-m-d'),
            'time_start' => $this->faker->time('H:i'),
            'time_end' => $this->faker->time('H:i'),
            'RequesterName' => $this->faker->name,
            'RequesterSignature' => 'data:image/png;base64,' . base64_encode(file_get_contents($this->faker->image())),
            'npersons' => $this->faker->numberBetween(1, 100),
            'tables' => $this->faker->optional()->numberBetween(0, 20),
            'chairs' => $this->faker->optional()->numberBetween(0, 100),
            'otherFacilities' => $this->faker->optional()->randomElement(['Projector', 'Sound System', 'Microphone']),
            'CRoomID' => $conferenceRoomID,
            'FormStatus' => 'Pending',
            'EventStatus' => '-',
        ];
    }
}