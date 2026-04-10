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
        // Create products table with foreign keys
        // Note: categories and brands tables are created in their own migrations
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Core Product Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('body')->nullable();
            $table->string('image')->nullable();

            // Pricing & Stock
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->string('sku')->unique()->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->boolean('track_quantity')->default(true);

            // Inventory & Product Details
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->string('barcode')->nullable();
            $table->decimal('weight', 10, 3)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();

            // Relationships & Categorization
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');

            // Status & Visibility
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->enum('type', ['simple', 'variable', 'digital', 'booking'])->default('simple');

            // Flexible JSON column for attributes
            $table->json('attributes')->nullable();

            // Additional Product Data
            $table->json('tags')->nullable();
            $table->string('warranty')->nullable();
            $table->string('video_url')->nullable();

            // SEO Meta
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->json('seo_keywords')->nullable();

            // Publishing
            $table->timestamp('published_at')->nullable();

            // Timestamps & Soft Deletes
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('is_visible');
            $table->index('is_featured');
            $table->index('type');
            $table->index('created_at');
            $table->index('category_id');
            $table->index('brand_id');
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