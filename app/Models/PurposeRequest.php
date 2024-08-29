<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurposeRequest extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural form of the model
    protected $table = 'purpose_requests';
    protected $primaryKey = 'PurposeID';

    // Define the fillable attributes
    protected $fillable = ['request_p','PurposeID','purpose'];
}