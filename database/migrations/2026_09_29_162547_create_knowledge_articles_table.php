<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('ticket_id')->nullable()->constrained('tickets')->nullOnDelete();
            
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('helpful_count')->default(0);
            $table->unsignedInteger('not_helpful_count')->default(0);
            
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'is_published']);
        });

        // Tabel rating artikel (siapa yang vote)
        Schema::create('article_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('knowledge_articles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_helpful');
            $table->timestamps();

            $table->unique(['article_id', 'user_id']);
        });

        // Tabel tags
        Schema::create('article_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Pivot article_tag
        Schema::create('article_tag', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('knowledge_articles')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('article_tags')->onDelete('cascade');
            $table->primary(['article_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('article_tags');
        Schema::dropIfExists('article_ratings');
        Schema::dropIfExists('knowledge_articles');
    }
};