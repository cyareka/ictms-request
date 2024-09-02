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
    public $timestamps = true;
    public $incrementing = false; 
    protected $keyType = 'string'; 

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            $model->updated_at = now();
        });
    }

    protected $fillable = [
        'VRequestID',
        'OfficeID',
        'PurposeID',
        'PurposeOthers',
        'passengers',
        'date_start',
        'date_end',
        'time_start',
        'Destination',
        'RequesterName',
        'RequesterContact',
        'RequesterEmail',
        'RequesterSignature',
        'IPAddress',

        // to be filled by dispatcher
        'DriverID',
        'VehicleID',
        'VAvailability',
        'ReceivedBy',
        'Remarks',

        // to be filled by administrative service
        'Availability', // vehicle availability
        'AAID',
        'SOID',
        'ASignatory',
        'FormStatus',
        'certfile-upload',
        'EventStatus',
        'ContactNo',
        'DriverEmail',
        'plate',
        'AAPosition',
        'SOPosition',
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

    public function purpose_request(): BelongsTo
    {
        return $this->belongsTo(PurposeRequest::class, 'PurposeID');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'DriverID');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'EmployeeID');
    }

    public function SOAuth(): BelongsTo
    {
        return $this->belongsTo(SOAuthority::class, 'SOID');
    }

    public function AAuth(): BelongsTo
    {
        return $this->belongsTo(AAuthority::class, 'AAID');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ReceivedBy');
    }
}
