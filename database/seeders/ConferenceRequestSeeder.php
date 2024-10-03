<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConferenceRequest;

class ConferenceRequestSeeder extends Seeder
{
    public function run()
    {
        ConferenceRequest::factory()->count(50)->create();
    }
}