<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';
    protected $primaryKey = 'DriverID';

    protected $fillable = [
        'DriverID',
        'DriverName',
        'DriverEmail',
        'ContactNo',
        'Availability',
    ];

    protected $casts = [
        'date_start' => 'array',
        'date_end' => 'array',
        'time_start' => 'array',
        'time_end' => 'array',
    ];
}
