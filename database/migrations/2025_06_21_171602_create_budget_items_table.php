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
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->string('category'); // e.g., 'Venue', 'Catering', 'Materials', 'Transport'
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0.00); // Calculated as quantity * unit_cost
            $table->string('unit_of_measure')->nullable(); // per person, per day, lump sum, etc.
            $table->text('justification')->nullable(); // Why this item is needed
            $table->boolean('is_approved')->default(false);
            $table->decimal('approved_amount', 10, 2)->nullable();
            $table->text('approval_notes')->nullable();
            $table->integer('priority')->default(3); // 1-5 scale
            $table->boolean('is_mandatory')->default(true);
            $table->json('vendors')->nullable(); // Potential vendors/suppliers
            $table->timestamps();
            
            // Indexes
            $table->index(['budget_id', 'category']);
            $table->index('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};
