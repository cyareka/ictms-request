<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\IDGenerator;
use App\Models\ConferenceRoom;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConferenceRoom>
 */
class ConferenceRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ConferenceRoom::class;

    public function definition(): array
    {
        $idGenerator = new IDGenerator();
        return [
            'CRoomID' => $idGenerator->generateID_3(),
            'Availability' => 'Available',
            'CRoomName' => $this->faker->name(),
            'Location' => $this->faker->city(),
            'Capacity' => $this->faker->numberBetween(1, 50),
        ];
    }

    public function magiting(): Factory
    {
        return $this->state(function (array $attributes) {
            $idGenerator = new IDGenerator();
            return [
                'CRoomID' => $idGenerator->generateID_3(),
                'CRoomName' => 'Magiting',
                'Capacity' => 50,
                'Location' => 'Diamond Building',
            ];
        });
    }

    public function maagap(): Factory
    {
        return $this->state(function (array $attributes) {
            $idGenerator = new IDGenerator();
            return [
                'CRoomID' => $idGenerator->generateID_3(),
                'CRoomName' => 'Maagap',
                'Capacity' => 20,
                'Location' => 'Emerald Building',
            ];
        });
    }
}
