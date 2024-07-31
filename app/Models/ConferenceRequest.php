<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'CRequestID',
        'BasInID',
        'CRoomID',
        'EquipID',
        'Purpose',
        'npersons',
        'focalPerson',
        'tables',
        'chairs',
        'otherFacilities',
        'FormStatus',
        'EventStatus',
        'signature_path',
    ];

     protected $casts = [
        'date_start' => 'array',
        'date_end' => 'array',
        'time_start' => 'array',
        'time_end' => 'array',
    ];
}
