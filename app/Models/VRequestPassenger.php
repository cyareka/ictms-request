<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VRequestPassenger extends Model
{
    use HasFactory;

    protected $primaryKey = 'VRPassID';
    protected $table = 'vrequest_passenger';

    protected $fillable = [
        'VRPassID',
        'VRequestID',
        'EmployeeID',
    ];

    public function vehicleRequest(): BelongsTo
    {
        return $this->belongsTo(VehicleRequest::class, 'VRequestID');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'EmployeeID');
    }
}
