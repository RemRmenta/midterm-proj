<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * RequestAssignment Model
 *
 * Connects service requests to assigned service workers.
 *
 * Attributes:
 * @property int $service_request_id
 * @property int $worker_id
 * @property datetime $assigned_at
 * @property datetime|null $completed_at
 */
class RequestAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'worker_id',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /* ======================
        Relationships
    =======================*/

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    /* ======================
        Helper Methods
    =======================*/

    public function markAsCompleted()
    {
        $this->update(['completed_at' => now()]);
        $this->serviceRequest()->update(['status' => 'completed']);
    }

    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }
}
