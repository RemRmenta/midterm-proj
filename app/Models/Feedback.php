<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Feedback Model
 *
 * Stores ratings and comments given by residents
 * after a service request has been completed.
 *
 * Attributes:
 * @property int $service_request_id
 * @property int $rating (1–5)
 * @property string|null $comment
 */
class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'rating',
        'comment',
    ];

    /* ======================
        Relationships
    =======================*/

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /* ======================
        Accessors
    =======================*/

    public function getResidentNameAttribute(): ?string
    {
        return $this->serviceRequest?->resident?->name;
    }

    public function getWorkerNameAttribute(): ?string
    {
        return $this->serviceRequest?->assignment?->worker?->name;
    }
}
