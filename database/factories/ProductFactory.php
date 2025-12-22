<?php

namespace Database\Factories;

use Alirezasedghi\LaravelImageFaker\ImageFaker;
use Alirezasedghi\LaravelImageFaker\Services\Picsum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $imageFaker = new ImageFaker(new Picsum());

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(10),
            'body' => $this->faker->paragraph(5),
            'image' => $imageFaker->image(storage_path('app/public/products')), // Downloads image
            // Or: 'image_url' => $imageFaker->imageUrl(), // Gets image URL
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'sale_price' => $this->faker->optional()->randomFloat(2, 5, 900),
            'sku' => strtoupper(Str::random(8)),
            'quantity' => $this->faker->numberBetween(0, 200),
            'track_quantity' => $this->faker->boolean(80),
            'category_id' => null, // optional for demo
            'brand_id' => null,
            'is_visible' => $this->faker->boolean(70),
            'is_featured' => $this->faker->boolean(20),
            'type' => $this->faker->randomElement(['simple', 'variable', 'digital', 'booking']),
            'attributes' => [
                'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
                'color' => $this->faker->safeColorName(),
            ],
            'seo_title' => ucfirst($name),
            'seo_description' => $this->faker->sentence(12),
        ];
    }
}
