<?php

namespace App\Services;

use App\Models\Holiday;
use App\Models\SlaConfig;
use App\Models\WorkingHour;
use Carbon\Carbon;

class SlaService
{
    /**
     * Hitung deadline SLA berdasarkan prioritas & tipe (response/resolution)
     * HANYA menghitung dalam jam kerja
     */
    public function calculateDeadline(string $priority, string $type, ?Carbon $from = null): Carbon
    {
        $from = $from ? $from->copy() : now();
        $config = SlaConfig::where('priority', $priority)->first();

        if (!$config) {
            // Fallback 24 jam
            return $from->copy()->addDay();
        }

        $minutes = $type === 'response' 
            ? $config->response_minutes 
            : $config->resolution_minutes;

        return $this->addWorkingMinutes($from, $minutes);
    }

    /**
     * Tambahkan X menit ke waktu tertentu, melewati jam non-kerja & hari libur
     */
    public function addWorkingMinutes(Carbon $start, int $minutesToAdd): Carbon
    {
        $current = $start->copy();
        $remaining = $minutesToAdd;

        // Kalau start di luar jam kerja, geser ke jam kerja berikutnya
        $current = $this->nextWorkingTime($current);

        $safetyCounter = 0;
        $maxIterations = 200; // prevent infinite loop

        while ($remaining > 0 && $safetyCounter < $maxIterations) {
            $safetyCounter++;

            // Ambil jam kerja hari ini
            $todayWorking = $this->getWorkingHourFor($current->dayOfWeek);

            if (!$todayWorking || $this->isHoliday($current)) {
                // Skip ke hari berikutnya, mulai dari jam kerja
                $current = $current->copy()->addDay()->startOfDay();
                $current = $this->nextWorkingTime($current);
                continue;
            }

            $startTime = $current->copy()->setTimeFromTimeString($todayWorking->start_time);
            $endTime = $current->copy()->setTimeFromTimeString($todayWorking->end_time);

            // Kalau sekarang sebelum jam buka, geser ke jam buka
            if ($current->lt($startTime)) {
                $current = $startTime->copy();
            }

            // Kalau sudah lewat jam tutup, lanjut hari berikutnya
            if ($current->gte($endTime)) {
                $current = $current->copy()->addDay()->startOfDay();
                $current = $this->nextWorkingTime($current);
                continue;
            }

            // Hitung menit yang tersedia hari ini
            $minutesUntilEndOfDay = $current->diffInMinutes($endTime, false);

            if ($remaining <= $minutesUntilEndOfDay) {
                // Selesai hari ini
                $current = $current->copy()->addMinutes($remaining);
                $remaining = 0;
            } else {
                // Habiskan hari ini, lanjut hari berikutnya
                $remaining -= $minutesUntilEndOfDay;
                $current = $current->copy()->addDay()->startOfDay();
                $current = $this->nextWorkingTime($current);
            }
        }

        return $current;
    }

    /**
     * Dapatkan waktu jam kerja berikutnya (kalau di luar jam kerja)
     */
    public function nextWorkingTime(Carbon $time): Carbon
    {
        $current = $time->copy();
        $safety = 0;

        while ($safety < 30) {
            $safety++;

            $workingHour = $this->getWorkingHourFor($current->dayOfWeek);

            if (!$workingHour || !$workingHour->is_working_day || $this->isHoliday($current)) {
                $current = $current->copy()->addDay()->startOfDay();
                continue;
            }

            $startTime = $current->copy()->setTimeFromTimeString($workingHour->start_time);
            $endTime = $current->copy()->setTimeFromTimeString($workingHour->end_time);

            if ($current->lt($startTime)) {
                return $startTime;
            }

            if ($current->between($startTime, $endTime)) {
                return $current;
            }

            // Sudah lewat, lanjut hari berikutnya
            $current = $current->copy()->addDay()->startOfDay();
        }

        return $current;
    }

    /**
     * Hitung berapa menit kerja antara dua waktu
     */
    public function workingMinutesBetween(Carbon $start, Carbon $end): int
    {
        if ($start->gte($end)) return 0;

        $minutes = 0;
        $current = $start->copy();
        $safety = 0;

        while ($current->lt($end) && $safety < 200) {
            $safety++;

            $workingHour = $this->getWorkingHourFor($current->dayOfWeek);

            if (!$workingHour || !$workingHour->is_working_day || $this->isHoliday($current)) {
                $current = $current->copy()->addDay()->startOfDay();
                continue;
            }

            $dayStart = $current->copy()->setTimeFromTimeString($workingHour->start_time);
            $dayEnd = $current->copy()->setTimeFromTimeString($workingHour->end_time);

            $effectiveStart = $current->gt($dayStart) ? $current->copy() : $dayStart->copy();
            $effectiveEnd = $end->lt($dayEnd) ? $end->copy() : $dayEnd->copy();

            if ($effectiveStart->lt($effectiveEnd)) {
                $minutes += $effectiveStart->diffInMinutes($effectiveEnd);
            }

            $current = $current->copy()->addDay()->startOfDay();
        }

        return $minutes;
    }

    /**
     * Hitung sisa waktu (menit kerja) sampai deadline
     */
    public function remainingWorkingMinutes(Carbon $deadline): int
    {
        if (now()->gte($deadline)) return 0;
        return $this->workingMinutesBetween(now(), $deadline);
    }

    /**
     * Persentase SLA terpakai (0-100)
     */
    public function slaPercentage(Carbon $createdAt, Carbon $deadline): float
    {
        $total = $this->workingMinutesBetween($createdAt, $deadline);
        if ($total <= 0) return 100;

        $elapsed = $this->workingMinutesBetween($createdAt, now());
        return min(100, round(($elapsed / $total) * 100, 2));
    }

    /**
     * Ambil jam kerja untuk hari tertentu
     */
    private function getWorkingHourFor(int $dayOfWeek): ?WorkingHour
    {
        return WorkingHour::where('day_of_week', $dayOfWeek)->first();
    }

    /**
     * Cek apakah tanggal tertentu hari libur
     */
    private function isHoliday(Carbon $date): bool
    {
        return Holiday::whereDate('date', $date->toDateString())->exists();
    }

    /**
     * Format menit jadi teks human-readable
     */
    public function humanMinutes(int $minutes): string
    {
        if ($minutes <= 0) return '0 menit';
        if ($minutes < 60) return "{$minutes} menit";

        $hours = intdiv($minutes, 60);
        $remainder = $minutes % 60;

        if ($hours >= 24) {
            $days = intdiv($hours, 24);
            $hoursLeft = $hours % 24;
            $text = "{$days} hari";
            if ($hoursLeft > 0) $text .= " {$hoursLeft} jam";
            return $text;
        }

        return $remainder > 0 ? "{$hours} jam {$remainder} menit" : "{$hours} jam";
    }
}