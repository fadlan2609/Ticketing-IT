<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    use HasFactory;

    protected $fillable = ['day_of_week', 'start_time', 'end_time', 'is_working_day'];

    protected $casts = ['is_working_day' => 'boolean'];

    public function getDayNameAttribute(): string
    {
        return ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$this->day_of_week];
    }
}