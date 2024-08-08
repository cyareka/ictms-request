<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleRequest extends Model
{
    use HasFactory;
    protected $table = 'vehicle_request';
    protected $primaryKey = 'VRequestID';

    protected $fillable = [
        'VRequestID',
        'OfficeID',
        'purpose',
        'passengers',
        'date_start',
        'date_end',
        'time_start',
        'location',
        'RequesterName',
        'RequesterContact',
        'RequesterEmail',
        'RequesterSignature',
        'IPAddress',

        // to be filled by dispatcher
        'DriverID',
        'VehicleID',
        'ReceivedBy',

        // to be filled by administrative service
        'Availability', // vehicle availability
        'FormStatus',
        'EventStatus',
    ];

    protected $casts = [
        'passengers' => 'array',
        'date_start' => 'array',
        'date_end' => 'array',
        'time_start' => 'array',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'OfficeID');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'DriverID');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'EmployeeID');
    }
}
