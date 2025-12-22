<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::factory()->count(5)->create();
        $categories->each(function ($category) {
            Category::factory()->count(3)->create([
                'parent_id' => $category->id,
            ]);
        });
    }
}
