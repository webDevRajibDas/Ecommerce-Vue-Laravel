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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to products table
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // Variation types (e.g., ['size', 'color'])
            $table->json('variation_types')->nullable();
            
            // Variation options
            $table->json('size_options')->nullable();
            $table->json('color_options')->nullable();
            $table->json('material_options')->nullable();
            $table->json('pattern_options')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
