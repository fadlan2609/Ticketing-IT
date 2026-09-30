<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number', 'title', 'description',
        'category_id', 'requester_id', 'assigned_to_id', 'branch_id',
        'priority', 'status',
        'sla_response_deadline', 'sla_resolution_deadline',
        'responded_at', 'resolved_at', 'closed_at',
        'is_sla_breached', 'resolution_note',
    ];

    protected $casts = [
        'sla_response_deadline' => 'datetime',
        'sla_resolution_deadline' => 'datetime',
        'responded_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'is_sla_breached' => 'boolean',
    ];

    // ===== Relasi =====
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class)->latest();
    }

    public function publicComments()
    {
        return $this->hasMany(TicketComment::class)
                    ->where('is_internal_note', false)
                    ->latest();
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function activities()
    {
        return $this->hasMany(TicketActivity::class)->latest();
    }

    // ===== Helper =====
    public static function generateTicketNumber(): string
    {
        $prefix = 'TKT-' . date('Ym') . '-';
        $lastTicket = self::where('ticket_number', 'like', $prefix . '%')
                          ->orderBy('ticket_number', 'desc')
                          ->first();

        if ($lastTicket) {
            $lastNumber = (int) substr($lastTicket->ticket_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'new' => '<span class="badge bg-brand-100 text-brand-800">New</span>',
            'assigned' => '<span class="badge bg-blue-100 text-blue-800">Assigned</span>',
            'in_progress' => '<span class="badge bg-blue-100 text-blue-800">In Progress</span>',
            'pending' => '<span class="badge bg-orange-100 text-orange-800">Pending</span>',
            'resolved' => '<span class="badge bg-green-100 text-green-800">Resolved</span>',
            'closed' => '<span class="badge bg-gray-100 text-gray-700">Closed</span>',
            'reopened' => '<span class="badge bg-danger-100 text-danger-700">Reopened</span>',
            'cancelled' => '<span class="badge bg-gray-100 text-gray-500">Cancelled</span>',
            default => '<span class="badge bg-gray-100 text-gray-700">-</span>',
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match ($this->priority) {
            'low' => '<span class="badge bg-green-100 text-green-700">🟢 Low</span>',
            'medium' => '<span class="badge bg-brand-100 text-brand-800">🟡 Medium</span>',
            'high' => '<span class="badge bg-orange-100 text-orange-800">🟠 High</span>',
            'critical' => '<span class="badge bg-danger-100 text-danger-700">🔴 Critical</span>',
            default => '-',
        };
    }

    public function getSlaStatusAttribute(): array
    {
        if (!$this->sla_resolution_deadline) {
            return ['color' => 'gray', 'text' => 'No SLA', 'percent' => 0];
        }

        // Kalau sudah resolved/closed
        if (in_array($this->status, ['resolved', 'closed'])) {
            return ['color' => 'green', 'text' => 'Selesai', 'percent' => 100];
        }

        // Kalau dibatalkan
        if ($this->status === 'cancelled') {
            return ['color' => 'gray', 'text' => 'Dibatalkan', 'percent' => 0];
        }

        $sla = app(\App\Services\SlaService::class);

        // Cek breach
        if (now()->gte($this->sla_resolution_deadline)) {
            $overdueMinutes = $sla->workingMinutesBetween($this->sla_resolution_deadline, now());
            // kalau di luar jam kerja, tidak dihitung overdue
            if ($overdueMinutes > 0) {
                return [
                    'color' => 'red',
                    'text' => 'BREACH (' . $sla->humanMinutes($overdueMinutes) . ' lewat)',
                    'percent' => 100,
                ];
            }
        }

        $remaining = $sla->remainingWorkingMinutes($this->sla_resolution_deadline);
        $percent = $sla->slaPercentage($this->created_at, $this->sla_resolution_deadline);

        if ($percent >= 80) {
            return [
                'color' => 'orange',
                'text' => 'Sisa ' . $sla->humanMinutes($remaining),
                'percent' => $percent,
            ];
        }

        return [
            'color' => 'green',
            'text' => 'Sisa ' . $sla->humanMinutes($remaining),
            'percent' => $percent,
        ];
    }
}