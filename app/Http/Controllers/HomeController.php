<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use Laravel\Fortify\Features;

class HomeController extends Controller
{
    public function index()
    {
        // Get active categories with product count, ordered by position
        $categories = Category::active()
            ->withCount('products')
            ->orderBy('position')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon ?? 'pi-shopping-bag',
                    'count' => $category->products_count,
                    'badge' => $category->badge ?? '',
                ];
            });

        $featuredProducts = Product::query()
            ->with(['category', 'mainImage'])
            ->where('is_visible', true)
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($product) {
                $inventoryStatus = 'OUTOFSTOCK';

                if ($product->quantity > $product->low_stock_threshold) {
                    $inventoryStatus = 'INSTOCK';
                } elseif ($product->quantity > 0) {
                    $inventoryStatus = 'LOWSTOCK';
                }

                return [
                    'id' => (string) $product->id,
                    'name' => $product->name,
                    'category' => $product->category?->name ?? 'Uncategorized',
                    'price' => (float) ($product->sale_price ?? $product->price),
                    'originalPrice' => $product->sale_price ? (float) $product->price : null,
                    'image' => $product->main_image_url
                        ?? ($product->image ? asset('storage/' . ltrim($product->image, '/')) : null),
                    'inventoryStatus' => $inventoryStatus,
                    'rating' => 0,
                ];
            });

        return Inertia::render('Home', [
            'canRegister' => Features::enabled(Features::registration()),
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'slides' => [
                [
                    'image' => '/images/slider-1.jpg',
                    'title' => 'Summer Collection 2023',
                    'description' => 'Discover our latest summer dresses and accessories'
                ],
            ]
        ]);
    }
}
