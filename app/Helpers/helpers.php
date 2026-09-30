<?php

use App\Models\CompanySetting;

if (!function_exists('setting')) {
    /**
     * Ambil nilai setting perusahaan.
     * 
     * Penggunaan:
     *   setting('company_name')         → string
     *   setting('logo_url')             → string (URL)
     *   setting()                        → objek CompanySetting lengkap
     */
    function setting(?string $key = null, $default = null)
    {
        $settings = CompanySetting::instance();

        if ($key === null) {
            return $settings;
        }

        return $settings->{$key} ?? $default;
    }
}