<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'DriverID',
        'VehicleID',
        'requesting_office',
        'purpose',
        'passengers',
        'date_start',
        'date_end',
        'time_start',
        'place_of_travel',
        'requested_by',
        'email',
        'contact_no',
        'e_signature',
        'FormStatus',
        'EventStatus',
    ];

    protected $casts = [
        'passengers' => 'array',
        'date_start' => 'array',
        'date_end' => 'array',
        'time_start' => 'array',
        'time_end' => 'array',
    ];
}
