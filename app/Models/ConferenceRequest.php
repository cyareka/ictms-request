<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'CRequestID';

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
        'requesterName',
        'dateStart',
        'dateEnd',
        'timeStart',
        'timeEnd',
    ];

    protected $casts = [
        'dateStart' => 'array',
        'dateEnd' => 'array',
        'timeStart' => 'array',
        'timeEnd' => 'array',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'OfficeID');
    }

    public function conferenceRoom()
    {
        return $this->belongsTo(ConferenceRoom::class, 'CRoomID');
    }
}