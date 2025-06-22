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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_code')->unique(); // Auto-generated unique code
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['completion', 'participation', 'achievement', 'recognition']);
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Certificate recipient
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade'); // Related event
            $table->foreignId('institution_id')->constrained()->onDelete('cascade'); // Issuing institution
            $table->foreignId('issued_by')->constrained('users')->onDelete('cascade'); // Who issued it
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'revoked', 'expired'])->default('active');
            $table->string('verification_hash')->unique(); // For public verification
            $table->json('certificate_data')->nullable(); // Additional certificate info
            $table->string('template_used')->nullable(); // Certificate template reference
            $table->text('special_notes')->nullable();
            $table->string('file_path')->nullable(); // Generated PDF file path
            $table->foreignId('revoked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['event_id', 'type']);
            $table->index(['institution_id', 'issue_date']);
            $table->index('verification_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
