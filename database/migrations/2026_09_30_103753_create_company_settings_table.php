<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();

            // Brand
            $table->string('app_name')->default('Amanah Service Desk');
            $table->string('company_name')->default('BPRS Amanah Bangsa');
            $table->string('company_short_name')->default('BPRS AB');
            $table->string('tagline')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('logo_dark_path')->nullable(); // untuk background terang
            $table->string('favicon_path')->nullable();

            // Kontak
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Legal / Perbankan
            $table->string('npwp')->nullable();
            $table->string('license_number')->nullable();
            $table->string('established_year', 4)->nullable();

            // Sosial Media
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();

            // Kop Laporan (PDF)
            $table->text('report_header_text')->nullable();
            $table->text('report_footer_text')->nullable();

            // Pengaturan lain
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('locale', 10)->default('id');
            $table->string('currency', 10)->default('IDR');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};