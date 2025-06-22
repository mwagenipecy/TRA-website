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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'tra_officer', 'leader', 'supervisor', 'student'
            $table->string('display_name'); // e.g., 'TRA Officer', 'Club Leader'
            $table->text('description')->nullable();
            $table->json('permissions')->nullable(); // Array of permission strings
            $table->boolean('is_system_role')->default(false); // System roles cannot be deleted
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
