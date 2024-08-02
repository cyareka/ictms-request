<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceRequest extends Model
{
    use HasFactory;


    /**
     * @var false|mixed|string
     */
    public mixed $RequesterSignature;
    protected $fillable = [
        'CRequestID',
        'OfficeID',
        'Purpose',
        'npersons',
        'focalPerson',
        'tables',
        'chairs',
        'otherFacilities',
        'CRoomID',
        'FormStatus',
        'EventStatus',
        'RequesterSignature',
    ];

     protected $casts = [
        'date_start' => 'array',
        'date_end' => 'array',
        'time_start' => 'array',
        'time_end' => 'array',
    ];
}
