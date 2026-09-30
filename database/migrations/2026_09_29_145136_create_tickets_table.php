<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 30)->unique();
            $table->string('title');
            $table->text('description');
            
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('requester_id')->constrained('users');
            $table->foreignId('assigned_to_id')->nullable()->constrained('users');
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', [
                'new', 'assigned', 'in_progress', 'pending', 
                'resolved', 'closed', 'reopened', 'cancelled'
            ])->default('new');
            
            // SLA
            $table->timestamp('sla_response_deadline')->nullable();
            $table->timestamp('sla_resolution_deadline')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->boolean('is_sla_breached')->default(false);
            
            // Solusi
            $table->text('resolution_note')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'priority']);
            $table->index('assigned_to_id');
            $table->index('requester_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};