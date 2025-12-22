<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->company();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'logo' => $this->faker->imageUrl(200, 200, 'brand', true),
            'description' => $this->faker->sentence(10),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
