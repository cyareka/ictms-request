<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static whereDate(string $string, string $toDateString)
 * @method static where(string $string, string $string1)
 * @method static whereMonth(string $string, int $month)
 * @method static select(string $string, $raw)
 */
class ConferenceRequest extends Model
{
    use HasFactory;

    protected $table = 'conference_room_requests';
    protected $primaryKey = 'CRequestID';

    protected $fillable = [
        'CRequestID',
        'OfficeID',
        'PurposeID',
        'npersons',
        'focalPerson',
        'CAvailability',
        'focalPerson',
        'tables',
        'chairs',
        'otherFacilities',
        'CRoomID',
        'FormStatus',
        'EventStatus',
        'RequesterSignature',
        'RequesterName',
        'date_start',
        'date_end',
        'time_start',
        'time_end',
    ];

    protected $casts = [
        'date_start' => 'array',
        'date_end' => 'array',
        'time_start' => 'array',
        'time_end' => 'array',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'OfficeID');
    }

    public function conferenceRoom(): BelongsTo
    {
        return $this->belongsTo(ConferenceRoom::class, 'CRoomID');
    }
    public function purposeRequest() {
        return $this->belongsTo(PurposeRequest::class, 'PurposeID',); // Adjust as necessary
    }
}
