<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'nip',
        'phone',
        'photo',
        'branch_id',
        'division_id',
        'position',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relasi
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    // Helper
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    public function isITSupport(): bool
    {
        return $this->hasRole('IT Support');
    }

    public function isSupervisor(): bool
    {
        return $this->hasRole('Supervisor');
    }

    public function isRequester(): bool
    {
        return $this->hasRole('Requester');
    }

    public function isAuditor(): bool
    {
        return $this->hasRole('Auditor');
    }

    public function ticketsRequested()
    {
        return $this->hasMany(Ticket::class, 'requester_id');
    }

    public function ticketsAssigned()
    {
        return $this->hasMany(Ticket::class, 'assigned_to_id');
    }

    public function appNotifications()
    {
        return $this->hasMany(AppNotification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(AppNotification::class)->where('is_read', false);
    }
}