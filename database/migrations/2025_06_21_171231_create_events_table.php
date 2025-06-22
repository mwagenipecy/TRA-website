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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['workshop', 'seminar', 'training', 'conference', 'meeting', 'competition', 'other']);
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('venue');
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('max_participants')->nullable();
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->boolean('is_free')->default(true);
            $table->boolean('requires_approval')->default(false);
            $table->boolean('allow_non_members')->default(false);
            $table->datetime('registration_start')->nullable();
            $table->datetime('registration_end')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed', 'postponed'])->default('draft');
            $table->json('requirements')->nullable(); // What participants need to bring
            $table->json('objectives')->nullable(); // Learning objectives
            $table->json('target_audience')->nullable(); // Who should attend
            $table->string('banner_image')->nullable();
            $table->json('attachments')->nullable(); // Additional files
            $table->json('tags')->nullable(); // Event tags for categorization
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index(['institution_id', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
