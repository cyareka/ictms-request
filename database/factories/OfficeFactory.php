<?php

namespace Database\Factories;

use App\Models\Office;
use App\Helpers\IDGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Office>
 */
class OfficeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $idGenerator = new IDGenerator();

        return [
            'OfficeID' => $idGenerator->generateID_10(),
            'OfficeName' => fake()->company(),
            'OfficeLocation' => fake()->address(),
        ];
    }
}
