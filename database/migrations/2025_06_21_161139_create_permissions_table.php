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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'view-members', 'manage-budgets'
            $table->string('display_name'); // e.g., 'View Members', 'Manage Budgets'
            $table->text('description')->nullable();
            $table->string('category'); // e.g., 'members', 'events', 'budgets', 'system'
            $table->boolean('is_system_permission')->default(false);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
