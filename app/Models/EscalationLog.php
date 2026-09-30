<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscalationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'level', 'reason', 'from_user_id', 'to_user_id', 'is_automatic',
    ];

    protected $casts = ['is_automatic' => 'boolean'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}