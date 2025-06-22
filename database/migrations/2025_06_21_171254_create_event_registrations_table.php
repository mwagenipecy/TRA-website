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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_member')->default(true); // Whether user is a club member
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'attended', 'no_show'])->default('pending');
            $table->timestamp('registered_at');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('approval_notes')->nullable();
            $table->json('additional_info')->nullable(); // Special requirements, dietary restrictions, etc.
            $table->boolean('payment_required')->default(false);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->boolean('attended')->default(false);
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();
            $table->integer('rating')->nullable(); // Event rating 1-5
            $table->text('feedback')->nullable();
            $table->timestamps();
            
            // Indexes and constraints
            $table->unique(['event_id', 'user_id']);
            $table->index(['event_id', 'status']);
            $table->index(['user_id', 'status']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
