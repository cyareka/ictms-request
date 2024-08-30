<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FocalPerson extends Model
{
    use HasFactory;

    protected $table = 'focal_person';
    protected $primaryKey = 'FocalPID';

    protected $fillable = [
        'FocalPID',
        'FPName',
        'OfficeID'
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'OfficeID');
    }
}
