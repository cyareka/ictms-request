<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceRoom extends Model
{
    use HasFactory;

    protected $primaryKey = 'CRoomID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'CRoomID',
        'Availability',
        'CRoomName',
        'Location',
        'Capacity',
    ];

    protected $casts = [
        'date_start' => 'array',
        'date_end' => 'array',
        'time_start' => 'array',
        'time_end' => 'array',
    ];
}
