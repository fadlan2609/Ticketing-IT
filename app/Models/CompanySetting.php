<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name', 'company_name', 'company_short_name', 'tagline',
        'logo_path', 'logo_dark_path', 'favicon_path',
        'address', 'city', 'province', 'postal_code',
        'phone', 'whatsapp', 'email', 'website',
        'npwp', 'license_number', 'established_year',
        'facebook', 'instagram', 'twitter', 'linkedin', 'youtube',
        'report_header_text', 'report_footer_text',
        'timezone', 'locale', 'currency',
    ];

    /**
     * Ambil instance tunggal (singleton).
     * Kalau belum ada, buat dengan default.
     */
    public static function instance(): self
    {
        return Cache::rememberForever('company_setting', function () {
            return self::firstOrCreate(['id' => 1]);
        });
    }

    /**
     * Clear cache ketika diupdate.
     */
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('company_setting');
        });
        static::deleted(function () {
            Cache::forget('company_setting');
        });
    }

    /**
     * Helper: URL logo (dengan fallback)
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_path && file_exists(public_path($this->logo_path))) {
            return asset($this->logo_path);
        }
        return null;
    }

    public function getLogoDarkUrlAttribute(): ?string
    {
        if ($this->logo_dark_path && file_exists(public_path($this->logo_dark_path))) {
            return asset($this->logo_dark_path);
        }
        return $this->logo_url;
    }

    public function getFaviconUrlAttribute(): ?string
    {
        if ($this->favicon_path && file_exists(public_path($this->favicon_path))) {
            return asset($this->favicon_path);
        }
        return $this->logo_url;
    }

    /**
     * Alamat lengkap terformat
     */
    public function getFullAddressAttribute(): ?string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->province,
            $this->postal_code,
        ]);
        return count($parts) > 0 ? implode(', ', $parts) : null;
    }

    /**
     * Inisial untuk fallback logo (mis. "AB" dari "Amanah Bangsa")
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->company_short_name ?? $this->company_name ?? 'AB');
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials ?: 'AB';
    }
}