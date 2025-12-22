<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class PodcastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Ensure categories & brands exist first
        if (Category::count() === 0) {
            Category::factory()->count(5)->create();
        }

        if (Brand::count() === 0) {
            Brand::factory()->count(5)->create();
        }

        $categories = Category::all()->pluck('id')->toArray();
        $brands = Brand::all()->pluck('id')->toArray();

        Product::factory()->count(50)->make()->each(function ($product) use ($categories, $brands) {
            $product->category_id = fake()->randomElement($categories);
            $product->brand_id = fake()->randomElement($brands);
            $product->save();
        });
    }
}
