<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * User Model
 *
 * Represents system users — Admins, Residents, and Service Workers.
 *
 * Attributes:
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role  ('admin', 'resident', 'service_worker')
 * @property string|null $address
 * @property string|null $contact_number
 * @property string|null $profile_photo
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'address',
        'contact_number',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* ======================
        Relationships
    =======================*/

    // Resident → has many Service Requests
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'user_id');
    }

    // Worker → has many Assignments
    public function assignments()
    {
        return $this->hasMany(RequestAssignment::class, 'worker_id');
    }

    // Worker → has many Service Requests through assignments
    public function assignedRequests()
    {
        return $this->hasManyThrough(
            ServiceRequest::class,
            RequestAssignment::class,
            'worker_id',
            'id',
            'id',
            'service_request_id'
        );
    }

    /* ======================
        Helper Methods
    =======================*/

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isResident(): bool
    {
        return $this->role === 'resident';
    }

    public function isWorker(): bool
    {
        return $this->role === 'service_worker';
    }
}
