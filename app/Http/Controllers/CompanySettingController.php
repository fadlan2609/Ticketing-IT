<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanySettingController extends Controller
{
    /**
     * Form pengaturan perusahaan (Super Admin)
     */
    public function edit()
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $company = CompanySetting::instance();

        return view('settings.company', compact('company'));
    }

    /**
     * Update pengaturan perusahaan
     */
    public function update(Request $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $request->validate([
            // Brand
            'app_name' => ['required', 'string', 'max:100'],
            'company_name' => ['required', 'string', 'max:150'],
            'company_short_name' => ['required', 'string', 'max:50'],
            'tagline' => ['nullable', 'string', 'max:200'],

            // Upload files
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'logo_dark' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:png,jpg,jpeg,ico,svg', 'max:512'],

            // Kontak
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'website' => ['nullable', 'url', 'max:150'],

            // Legal
            'npwp' => ['nullable', 'string', 'max:50'],
            'license_number' => ['nullable', 'string', 'max:100'],
            'established_year' => ['nullable', 'digits:4'],

            // Sosmed
            'facebook' => ['nullable', 'url', 'max:200'],
            'instagram' => ['nullable', 'url', 'max:200'],
            'twitter' => ['nullable', 'url', 'max:200'],
            'linkedin' => ['nullable', 'url', 'max:200'],
            'youtube' => ['nullable', 'url', 'max:200'],

            // Kop laporan
            'report_header_text' => ['nullable', 'string', 'max:1000'],
            'report_footer_text' => ['nullable', 'string', 'max:1000'],
        ]);

        $company = CompanySetting::instance();

        // Upload logo utama
        if ($request->hasFile('logo')) {
            $this->deleteOldFile($company->logo_path);
            $company->logo_path = $this->uploadFile($request->file('logo'), 'company');
        }

        // Upload logo dark
        if ($request->hasFile('logo_dark')) {
            $this->deleteOldFile($company->logo_dark_path);
            $company->logo_dark_path = $this->uploadFile($request->file('logo_dark'), 'company');
        }

        // Upload favicon
        if ($request->hasFile('favicon')) {
            $this->deleteOldFile($company->favicon_path);
            $company->favicon_path = $this->uploadFile($request->file('favicon'), 'company');
        }

        // Update field lainnya
        $company->update($request->except(['logo', 'logo_dark', 'favicon']));

        // Clear cache biar otomatis refresh di semua halaman
        \Cache::forget('company_setting');

        return redirect()->route('settings.company.edit')
            ->with('success', 'Pengaturan perusahaan berhasil diperbarui.');
    }

    /**
     * Hapus logo saja (tanpa upload baru)
     */
    public function destroyLogo(Request $request, string $type)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $company = CompanySetting::instance();

        $column = match ($type) {
            'logo' => 'logo_path',
            'logo_dark' => 'logo_dark_path',
            'favicon' => 'favicon_path',
            default => null,
        };

        if (!$column) {
            return back()->with('error', 'Tipe logo tidak valid.');
        }

        $this->deleteOldFile($company->{$column});
        $company->update([$column => null]);
        \Cache::forget('company_setting');

        return back()->with('success', 'Logo berhasil dihapus.');
    }

    /**
     * Helper upload file
     */
    private function uploadFile($file, string $folder): string
    {
        // Simpan di public/{folder}
        $filename = uniqid() . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $filename;

        // Simpan ke public/ langsung
        $file->move(public_path($folder), $filename);

        return $path;
    }

    /**
     * Helper hapus file lama
     */
    private function deleteOldFile(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}