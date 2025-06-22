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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('budget_code')->unique(); // Auto-generated unique code
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['yearly', 'event', 'project', 'emergency', 'equipment']);
            $table->text('description');
            $table->json('objectives')->nullable(); // Budget objectives
            $table->decimal('total_amount', 12, 2);
            $table->integer('financial_year'); // e.g., 2025
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected', 'revision_required', 'expired'])->default('draft');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('review_comments')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->json('revision_notes')->nullable();
            $table->decimal('approved_amount', 12, 2)->nullable();
            $table->decimal('spent_amount', 12, 2)->default(0);
            $table->decimal('remaining_amount', 12, 2)->nullable();
            $table->json('attachments')->nullable(); // Supporting documents
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // monthly, quarterly, yearly
            $table->integer('priority_level')->default(3); // 1-5 scale
            $table->softDeletes();

            $table->timestamps();
            
            // Indexes
            $table->index(['institution_id', 'status']);
            $table->index(['financial_year', 'status']);
            $table->index(['type', 'status']);
            $table->index('budget_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
