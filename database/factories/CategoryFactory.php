<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            ['name' => 'Electronics', 'icon' => 'pi-desktop', 'badge' => 'Hot'],
            ['name' => 'Mobile Phones', 'icon' => 'pi-mobile', 'badge' => 'Hot'],
            ['name' => 'Laptops & Computers', 'icon' => 'pi-desktop', 'badge' => ''],
            ['name' => "Men's Fashion", 'icon' => 'pi-user', 'badge' => ''],
            ['name' => "Women's Fashion", 'icon' => 'pi-users', 'badge' => 'Trending'],
            ['name' => 'Watches', 'icon' => 'pi-clock', 'badge' => ''],
            ['name' => 'Shoes & Footwear', 'icon' => 'pi-shopping-bag', 'badge' => ''],
            ['name' => 'Bags & Wallets', 'icon' => 'pi-briefcase', 'badge' => ''],
            ['name' => 'Home & Living', 'icon' => 'pi-home', 'badge' => 'Sale'],
            ['name' => 'Kitchen & Dining', 'icon' => 'pi-home', 'badge' => ''],
            ['name' => 'Furniture', 'icon' => 'pi-home', 'badge' => ''],
            ['name' => 'Beauty & Skincare', 'icon' => 'pi-star', 'badge' => 'New'],
            ['name' => 'Makeup & Cosmetics', 'icon' => 'pi-star', 'badge' => ''],
            ['name' => 'Health & Fitness', 'icon' => 'pi-heart', 'badge' => ''],
            ['name' => 'Sports & Outdoors', 'icon' => 'pi-heart', 'badge' => ''],
            ['name' => 'Toys & Kids', 'icon' => 'pi-users', 'badge' => 'New'],
            ['name' => 'Books & Stationery', 'icon' => 'pi-book', 'badge' => ''],
            ['name' => 'Jewelry & Accessories', 'icon' => 'pi-gem', 'badge' => ''],
            ['name' => 'Automotive', 'icon' => 'pi-car', 'badge' => ''],
            ['name' => 'Pet Supplies', 'icon' => 'pi-heart', 'badge' => ''],
        ];

        $category = $categories[array_rand($categories)];
        $name = $category['name'];

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(6),
            'icon' => $category['icon'],
            'badge' => $category['badge'] ?: null,
            'product_count' => $this->faker->numberBetween(10, 500),
            'is_active' => true,
            'parent_id' => null,
            'position' => $this->faker->numberBetween(1, 20),
        ];
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}