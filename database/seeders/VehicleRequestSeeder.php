<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleRequest;

class VehicleRequestSeeder extends Seeder
{
    public function run()
    {
        VehicleRequest::factory()->count(50)->create();
    }
}