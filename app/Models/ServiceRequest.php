<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ServiceRequest Model
 *
 * Represents a water service-related request made by a resident.
 *
 * Attributes:
 * @property int $user_id
 * @property string $type (e.g. 'Water Leak', 'New Connection', 'Meter Replacement')
 * @property string $description
 * @property string $address
 * @property string $priority ('low', 'medium', 'high')
 * @property string $status ('pending', 'in_progress', 'completed', 'cancelled')
 * @property string|null $proof_of_service (file path)
 */
class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'address',
        'priority',
        'status',
        'proof_of_service',
    ];

    /* ======================
        Relationships
    =======================*/

    // The resident who created the request
    public function resident()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // The assignment related to this request
    public function assignment()
    {
        return $this->hasOne(RequestAssignment::class, 'service_request_id');
    }

    // The feedback given for this request
    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'service_request_id');
    }

    /* ======================
        Accessors & Mutators
    =======================*/

    public function getProofOfServiceUrlAttribute(): ?string
    {
        return $this->proof_of_service 
            ? asset('storage/' . $this->proof_of_service)
            : null;
    }

    /* ======================
        Scopes
    =======================*/

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
