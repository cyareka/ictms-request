<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Superior extends Model
{
    use HasFactory;

    protected $table = 'superior';
    protected $primaryKey = 'SuperiorID';

    protected $fillable = [
        'SuperiorID',
        'SName',
        'Designation',
        'status',
    ];

}