<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Brand;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(rand(2, 4), true);

        $price = $this->faker->randomFloat(2, 100, 5000);

        // 30% chance of sale
        $salePrice = $this->faker->boolean(30)
            ? $price - $this->faker->randomFloat(2, 10, 200)
            : null;

        $quantity = $this->faker->numberBetween(0, 100);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name . '-' . uniqid()),

            'description' => $this->faker->sentence(12),
            'body' => $this->faker->paragraphs(3, true),

            'image' => 'products/' . $this->faker->image('public/storage/products', 640, 480, null, false),

            'price' => $price,
            'sale_price' => $salePrice,
            'cost_price' => $price * 0.7,

            'sku' => strtoupper(Str::random(8)),
            'quantity' => $quantity,
            'track_quantity' => true,
            'low_stock_threshold' => 5,

            'barcode' => $this->faker->ean13(),

            'weight' => $this->faker->randomFloat(3, 0.1, 5),
            'length' => $this->faker->randomFloat(2, 5, 50),
            'width' => $this->faker->randomFloat(2, 5, 50),
            'height' => $this->faker->randomFloat(2, 5, 50),

            'category_id' => Category::inRandomOrder()->value('id'),
            'brand_id' => Brand::inRandomOrder()->value('id'),

            'is_visible' => $this->faker->boolean(90),
            'is_featured' => $this->faker->boolean(20),

            'type' => $this->faker->randomElement(['simple', 'variable']),

            // JSON attributes
            'attributes' => json_encode([
                'color' => $this->faker->randomElement(['Red', 'Blue', 'Black', 'White']),
                'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            ]),

            'tags' => json_encode($this->faker->words(3)),

            'warranty' => $this->faker->randomElement(['6 months', '1 year', '2 years', null]),

            'video_url' => $this->faker->url(),

            'seo_title' => ucfirst($name),
            'seo_description' => $this->faker->sentence(10),
            'seo_keywords' => json_encode($this->faker->words(5)),

            'published_at' => now(),
        ];
    }
}
