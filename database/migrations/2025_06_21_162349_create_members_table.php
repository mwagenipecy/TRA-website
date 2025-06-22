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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('student_id')->nullable(); // Student registration number
            $table->string('course_of_study')->nullable();
            $table->integer('year_of_study')->nullable();
            $table->enum('member_type', ['student', 'leader', 'supervisor'])->default('student');
            $table->enum('status', ['active', 'inactive', 'pending', 'graduated', 'suspended'])->default('pending');
            $table->date('joined_date')->nullable();
            $table->date('graduation_date')->nullable();
            $table->json('interests')->nullable(); // Areas of tax interest
            $table->json('skills')->nullable(); // Member skills
            $table->text('motivation')->nullable(); // Why they joined
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index(['institution_id', 'status']);
            $table->index(['user_id', 'institution_id']);
            $table->unique(['user_id', 'institution_id']); // User can only be member of one institution
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};