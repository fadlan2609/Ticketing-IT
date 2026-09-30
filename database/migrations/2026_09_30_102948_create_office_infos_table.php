<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_infos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            
            // Kategori: announcement, policy, contact, hours, general, event
            $table->enum('category', [
                'announcement', 'policy', 'contact', 'hours', 'event', 'general'
            ])->default('general');
            
            // Untuk informasi kontak / jam operasional yang terstruktur
            $table->json('metadata')->nullable();
            
            // Icon (emoji atau nama icon)
            $table->string('icon', 20)->nullable();
            
            // Prioritas tampilan
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_published')->default(true);
            
            // Publish sampai kapan (untuk pengumuman dengan tenggat)
            $table->timestamp('published_until')->nullable();
            
            // Meta
            $table->foreignId('author_id')->constrained('users');
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_published', 'is_pinned']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_infos');
    }
};