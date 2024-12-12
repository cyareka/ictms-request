<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'yatinejohn@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'otp' => null,
            'otp_expires_at' => null,
            'remember_token' => Str::random(10),
            'current_team_id' => null,
            'profile_photo_path' => null,
            'added_by' => 'justinemesajon9@gmail.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}