<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'icon' => 'pi-desktop', 'badge' => 'Hot', 'position' => 1],
            ['name' => 'Mobile Phones', 'icon' => 'pi-mobile', 'badge' => 'Hot', 'position' => 2],
            ['name' => 'Laptops & Computers', 'icon' => 'pi-desktop', 'badge' => '', 'position' => 3],
            ['name' => "Men's Fashion", 'icon' => 'pi-user', 'badge' => '', 'position' => 4],
            ['name' => "Women's Fashion", 'icon' => 'pi-users', 'badge' => 'Trending', 'position' => 5],
            ['name' => 'Watches', 'icon' => 'pi-clock', 'badge' => '', 'position' => 6],
            ['name' => 'Shoes & Footwear', 'icon' => 'pi-shopping-bag', 'badge' => '', 'position' => 7],
            ['name' => 'Bags & Wallets', 'icon' => 'pi-briefcase', 'badge' => '', 'position' => 8],
            ['name' => 'Home & Living', 'icon' => 'pi-home', 'badge' => 'Sale', 'position' => 9],
            ['name' => 'Kitchen & Dining', 'icon' => 'pi-home', 'badge' => '', 'position' => 10],
            ['name' => 'Furniture', 'icon' => 'pi-home', 'badge' => '', 'position' => 11],
            ['name' => 'Beauty & Skincare', 'icon' => 'pi-star', 'badge' => 'New', 'position' => 12],
            ['name' => 'Makeup & Cosmetics', 'icon' => 'pi-star', 'badge' => '', 'position' => 13],
            ['name' => 'Health & Fitness', 'icon' => 'pi-heart', 'badge' => '', 'position' => 14],
            ['name' => 'Sports & Outdoors', 'icon' => 'pi-heart', 'badge' => '', 'position' => 15],
            ['name' => 'Toys & Kids', 'icon' => 'pi-users', 'badge' => 'New', 'position' => 16],
            ['name' => 'Books & Stationery', 'icon' => 'pi-book', 'badge' => '', 'position' => 17],
            ['name' => 'Jewelry & Accessories', 'icon' => 'pi-gem', 'badge' => '', 'position' => 18],
            ['name' => 'Automotive', 'icon' => 'pi-car', 'badge' => '', 'position' => 19],
            ['name' => 'Pet Supplies', 'icon' => 'pi-heart', 'badge' => '', 'position' => 20],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['name'] . ' - Browse our wide selection of quality ' . strtolower($category['name']) . ' products.',
                'icon' => $category['icon'],
                'badge' => $category['badge'] ?: null,
                'position' => $category['position'],
                'is_active' => true,
                'parent_id' => null,
                'product_count' => rand(50, 500),
            ]);
        }
    }
}