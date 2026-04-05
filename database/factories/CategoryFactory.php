<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name . '-' . uniqid()), // ✅ ensure unique slug
            'description' => $this->faker->sentence(8),
            'is_active' => $this->faker->boolean(90),
            'parent_id' => null, // will assign later if needed
            'position' => $this->faker->numberBetween(0, 20),
        ];
    }
}
