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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Core Product Information
            $table->string('name');
            $table->string('slug')->unique(); // For SEO-friendly URLs
            $table->text('description')->nullable(); // Short description
            $table->longText('body')->nullable(); // Full product details, specifications
            $table->string('image')->nullable();
            // Pricing & Stock

        
            $table->decimal('price', 10, 2); // Use decimal for currency to avoid float inaccuracies
            $table->decimal('sale_price', 10, 2)->nullable(); // For discounts
             $table->decimal('cost_price', 10, 2)->nullable();
            $table->string('sku')->unique(); // Stock Keeping Unit
            $table->unsignedInteger('quantity')->default(0); // Available stock
            $table->boolean('track_quantity')->default(true);

            // Relationships & Categorization
            // Assumes you have 'categories' and 'brands' tables
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');

            // Status & Visibility
            $table->boolean('is_visible')->default(false); // Controls if product is shown on the frontend
            $table->boolean('is_featured')->default(false); // To feature on the homepage
            $table->enum('type', ['simple', 'variable','digital','booking'])->default('simple');

            // ...OR a more flexible JSON column for attributes
            $table->json('attributes')->nullable(); // e.g., {"size": "XL", "color": "Blue"}


            // SEO Meta
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();

            $table->softDeletes(); // For soft-deleting products
            $table->timestamps();
        });

         Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->boolean('is_main')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });


        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->json('variation_types')->nullable(); // ['size', 'color']
            $table->json('size_options')->nullable(); // ['S', 'M', 'L']
            $table->json('color_options')->nullable(); // ['Red', 'Blue']
            $table->json('material_options')->nullable(); // ['Cotton', 'Silk']
            $table->json('pattern_options')->nullable(); // ['Striped', 'Solid']
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
