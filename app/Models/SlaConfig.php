<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaConfig extends Model
{
    use HasFactory;

    protected $fillable = ['priority', 'response_minutes', 'resolution_minutes', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function getResponseHumanAttribute(): string
    {
        return $this->minutesToHuman($this->response_minutes);
    }

    public function getResolutionHumanAttribute(): string
    {
        return $this->minutesToHuman($this->resolution_minutes);
    }

    private function minutesToHuman(int $minutes): string
    {
        if ($minutes < 60) return "{$minutes} menit";
        $hours = intdiv($minutes, 60);
        $remainder = $minutes % 60;
        if ($remainder === 0) return "{$hours} jam";
        return "{$hours} jam {$remainder} menit";
    }
}