<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // e.g., 'user_registered', 'event_created', 'budget_approved'
            $table->text('description');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Who performed the action
            $table->foreignId('institution_id')->nullable()->constrained()->onDelete('set null'); // Related institution
            $table->nullableMorphs('subject'); // The entity that was acted upon (polymorphic)
            $table->json('properties')->nullable(); // Additional data about the activity
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('performed_at');
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'performed_at']);
            $table->index(['institution_id', 'performed_at']);
            $table->index(['type', 'performed_at']);
           // $table->index(['subject_type', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
