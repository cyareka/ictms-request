<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\IDGenerator;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $idGenerator = new IDGenerator();
        $office = Office::factory()->create();

        return [
            'EmployeeID' => $idGenerator->generateID_10(),
            'EmployeeEmail' => fake()->unique()->userName() . '@dswd.gov.ph',
            'EmployeeName' => fake()->name(),
            'OfficeID' => $office->OfficeID,
            'EmployeeSignature' => $this->faker->imageUrl($width = 640, $height = 480, 'cats'),
        ];
    }
}
