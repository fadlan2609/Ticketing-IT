<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla_configs', function (Blueprint $table) {
            $table->id();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->unique();
            $table->unsignedInteger('response_minutes')->default(60);
            $table->unsignedInteger('resolution_minutes')->default(480);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Jam kerja bank
        Schema::create('working_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('day_of_week'); // 0=Minggu, 1=Senin ... 6=Sabtu
            $table->time('start_time')->default('08:00:00');
            $table->time('end_time')->default('17:00:00');
            $table->boolean('is_working_day')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('working_hours');
        Schema::dropIfExists('sla_configs');
    }
};