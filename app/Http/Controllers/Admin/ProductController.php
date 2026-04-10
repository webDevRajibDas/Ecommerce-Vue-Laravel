<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = Product::query()
            ->with(['category', 'brand', 'mainImage'])
            ->select('id', 'name', 'description', 'body', 'price', 'category_id', 'brand_id', 'sku', 'quantity', 'is_visible', 'is_featured', 'type', 'created_at')
            ->when($search, fn($query, $search) => $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            }))
            ->orderBy('id', 'desc')
            ->paginate(50)
            ->withQueryString();

        return Inertia::render('products/Products', [
            'productsData' => $products,
            'filters' => ['search' => $search],
            'totalProducts' => Product::count(),
            'categories' => $this->formatCategoriesForTreeSelect(
                Category::with('children')->where('is_active', true)->get()
            ),
            'brands' => Brand::where('is_active', true)->get(['id', 'name']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('children')->where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('products/Create', [
            'categories' => $this->formatCategoriesForTreeSelect($categories),
            'brands' => $brands,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //dd($request->all());
        $this->normalizeProductRequest($request);

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'description' => 'nullable|string',
            'body' => 'nullable|string',

            // Pricing
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',

            // Inventory
            'sku' => 'nullable|string|unique:products,sku',
            'quantity' => 'required|integer|min:0',
            'track_quantity' => 'nullable|boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'barcode' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',

            // Relations
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',

            // Status
            'is_visible' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'type' => 'required|in:simple,variable,digital,booking',

            // Additional
            'tags' => 'nullable|array',
            'attributes' => 'nullable|array',
            'warranty' => 'nullable|string',
            'video_url' => 'nullable|url',

            // SEO
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords' => 'nullable|array',

            // Timestamps
            'published_at' => 'nullable|date',

            // Images
            'image' => 'nullable|image|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|max:2048',

            // Variations
            'variations' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
                $count = Product::where('slug', 'like', "{$validated['slug']}%")->count();
                if ($count) {
                    $validated['slug'] = "{$validated['slug']}-{$count}";
                }
            }

            // Handle JSON fields that may come as strings from the frontend
            $tags = is_string($validated['tags'] ?? null) ? json_decode($validated['tags'], true) : ($validated['tags'] ?? null);
            $attributes = is_string($validated['attributes'] ?? null) ? json_decode($validated['attributes'], true) : ($validated['attributes'] ?? null);
            $seoKeywords = is_string($validated['seo_keywords'] ?? null) ? json_decode($validated['seo_keywords'], true) : ($validated['seo_keywords'] ?? null);

            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'body' => $validated['body'] ?? null,
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'cost_price' => $validated['cost_price'] ?? null,
                'sku' => $validated['sku'] ?? null,
                'quantity' => $validated['quantity'],
                'track_quantity' => $validated['track_quantity'] ?? false,
                'low_stock_threshold' => $validated['low_stock_threshold'] ?? null,
                'barcode' => $validated['barcode'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'length' => $validated['length'] ?? null,
                'width' => $validated['width'] ?? null,
                'height' => $validated['height'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'brand_id' => $validated['brand_id'] ?? null,
                'is_visible' => $validated['is_visible'] ?? false,
                'is_featured' => $validated['is_featured'] ?? false,
                'type' => $validated['type'],
                'tags' => $tags,
                'attributes' => $attributes,
                'warranty' => $validated['warranty'] ?? null,
                'video_url' => $validated['video_url'] ?? null,
                'seo_title' => $validated['seo_title'] ?? null,
                'seo_description' => $validated['seo_description'] ?? null,
                'seo_keywords' => $seoKeywords,
                'published_at' => $validated['published_at'] ?? now(),
            ]);

            // Store main image
            if ($request->hasFile('image')) {
                $mainImagePath = $request->file('image')->store('products/' . $product->id . '/main', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $mainImagePath,
                    'is_main' => true,
                    'order' => 0
                ]);
            }

            // Store gallery images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $file) {
                    $galleryPath = $file->store('products/' . $product->id . '/gallery', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $galleryPath,
                        'is_main' => false,
                        'order' => $index + 1
                    ]);
                }
            }

            // Save variations if product is variable
            $variations = $validated['variations'] ?? ($attributes['variations'] ?? null);
            if ($product->type === 'variable' && is_array($variations)) {

                ProductVariation::create([
                    'product_id' => $product->id,
                    'variation_types' => $variations['selectedTypes'] ?? [],
                    'size_options' => $variations['options']['size'] ?? null,
                    'color_options' => $variations['options']['color'] ?? null,
                    'material_options' => $variations['options']['material'] ?? null,
                    'pattern_options' => $variations['options']['pattern'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Failed to create product: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images', 'variations']);

        return Inertia::render('products/Show', [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['category', 'brand', 'images', 'variations']);
        $categories = Category::with('children')->where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('products/Edit', [
            'product' => $product,
            'categories' => $this->formatCategoriesForTreeSelect($categories),
            'brands' => $brands,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->normalizeProductRequest($request);

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'body' => 'nullable|string',

            // Pricing
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',

            // Inventory
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'quantity' => 'required|integer|min:0',
            'track_quantity' => 'nullable|boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'barcode' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',

            // Relations
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',

            // Status
            'is_visible' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'type' => 'required|in:simple,variable,digital,booking',

            // Additional
            'tags' => 'nullable|array',
            'attributes' => 'nullable|array',
            'warranty' => 'nullable|string',
            'video_url' => 'nullable|url',

            // SEO
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords' => 'nullable|array',

            // Timestamps
            'published_at' => 'nullable|date',

            // Images
            'image' => 'nullable|image|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'nullable|integer|exists:product_images,id',

            // Variations
            'variations' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
                $count = Product::where('slug', 'like', "{$validated['slug']}%")->where('id', '!=', $product->id)->count();
                if ($count) {
                    $validated['slug'] = "{$validated['slug']}-{$count}";
                }
            }

            // Handle JSON fields that may come as strings from the frontend
            $tags = is_string($validated['tags'] ?? null) ? json_decode($validated['tags'], true) : ($validated['tags'] ?? null);
            $attributes = is_string($validated['attributes'] ?? null) ? json_decode($validated['attributes'], true) : ($validated['attributes'] ?? null);
            $seoKeywords = is_string($validated['seo_keywords'] ?? null) ? json_decode($validated['seo_keywords'], true) : ($validated['seo_keywords'] ?? null);

            // Update product
            $product->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'body' => $validated['body'] ?? null,
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'cost_price' => $validated['cost_price'] ?? null,
                'sku' => $validated['sku'] ?? null,
                'quantity' => $validated['quantity'],
                'track_quantity' => $validated['track_quantity'] ?? false,
                'low_stock_threshold' => $validated['low_stock_threshold'] ?? null,
                'barcode' => $validated['barcode'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'length' => $validated['length'] ?? null,
                'width' => $validated['width'] ?? null,
                'height' => $validated['height'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'brand_id' => $validated['brand_id'] ?? null,
                'is_visible' => $validated['is_visible'] ?? false,
                'is_featured' => $validated['is_featured'] ?? false,
                'type' => $validated['type'],
                'tags' => $tags,
                'attributes' => $attributes,
                'warranty' => $validated['warranty'] ?? null,
                'video_url' => $validated['video_url'] ?? null,
                'seo_title' => $validated['seo_title'] ?? null,
                'seo_description' => $validated['seo_description'] ?? null,
                'seo_keywords' => $seoKeywords,
                'published_at' => $validated['published_at'] ?? $product->published_at,
            ]);

            // Handle image removal
            if ($request->has('remove_images')) {
                foreach ($request->input('remove_images') as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image) {
                        Storage::disk('public')->delete($image->path);
                        $image->delete();
                    }
                }
            }

            // Store new main image
            if ($request->hasFile('image')) {
                // Delete old main image
                $oldMainImage = ProductImage::where('product_id', $product->id)->where('is_main', true)->first();
                if ($oldMainImage) {
                    Storage::disk('public')->delete($oldMainImage->path);
                    $oldMainImage->delete();
                }

                $mainImagePath = $request->file('image')->store('products/' . $product->id . '/main', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $mainImagePath,
                    'is_main' => true,
                    'order' => 0
                ]);
            }

            // Store new gallery images
            if ($request->hasFile('gallery_images')) {
                $maxOrder = ProductImage::where('product_id', $product->id)->max('order') ?? 0;

                foreach ($request->file('gallery_images') as $index => $file) {
                    $galleryPath = $file->store('products/' . $product->id . '/gallery', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $galleryPath,
                        'is_main' => false,
                        'order' => $maxOrder + $index + 1
                    ]);
                }
            }

            // Save variations if product is variable
            $variations = $validated['variations'] ?? ($attributes['variations'] ?? null);
            if ($product->type === 'variable' && is_array($variations)) {

                $productVariation = ProductVariation::firstOrNew(['product_id' => $product->id]);
                $productVariation->variation_types = json_encode($variations['selectedTypes'] ?? []);
                $productVariation->size_options = isset($variations['options']['size']) ? json_encode($variations['options']['size']) : null;
                $productVariation->color_options = isset($variations['options']['color']) ? json_encode($variations['options']['color']) : null;
                $productVariation->material_options = isset($variations['options']['material']) ? json_encode($variations['options']['material']) : null;
                $productVariation->pattern_options = isset($variations['options']['pattern']) ? json_encode($variations['options']['pattern']) : null;
                $productVariation->save();
            } else {
                // Delete variations if product type is not variable
                ProductVariation::where('product_id', $product->id)->delete();
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Failed to update product: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            // Delete all product images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }

            // Delete product variations
            ProductVariation::where('product_id', $product->id)->delete();

            // Delete product
            $product->delete();

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Failed to delete product: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete multiple products.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:products,id'
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['ids'] as $productId) {
                $product = Product::find($productId);
                if ($product) {
                    // Delete all product images
                    foreach ($product->images as $image) {
                        Storage::disk('public')->delete($image->path);
                        $image->delete();
                    }

                    // Delete product variations
                    ProductVariation::where('product_id', $product->id)->delete();

                    // Delete product
                    $product->delete();
                }
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Products deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Failed to delete products: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Format categories for TreeSelect component.
     */
    private function formatCategoriesForTreeSelect($categories)
    {
        return $categories->map(function ($category) {
            $result = [
                'key' => $category->id,
                'label' => $category->name,
                'data' => ['id' => $category->id],
            ];

            if ($category->children->count() > 0) {
                $result['children'] = $category->children->map(function ($child) {
                    return [
                        'key' => $child->id,
                        'label' => $child->name,
                        'data' => ['id' => $child->id],
                    ];
                });
            }

            return $result;
        })->values();
    }

    /**
     * Get product data for modal editing.
     */
    public function getProduct(Product $product)
    {
        $product->load(['category', 'brand', 'images', 'variations']);

        return response()->json([
            'product' => $product,
            'categories' => $this->formatCategoriesForTreeSelect(
                Category::with('children')->where('is_active', true)->get()
            ),
            'brands' => Brand::where('is_active', true)->get(['id', 'name']),
        ]);
    }

    private function normalizeProductRequest(Request $request): void
    {
        $request->merge([
            'track_quantity' => $this->normalizeBoolean($request->input('track_quantity')),
            'is_visible' => $this->normalizeBoolean($request->input('is_visible')),
            'is_featured' => $this->normalizeBoolean($request->input('is_featured')),
            'category_id' => $this->normalizeNullableId($request->input('category_id')),
            'brand_id' => $this->normalizeNullableId($request->input('brand_id')),
            'tags' => $this->decodeJsonField($request->input('tags')),
            'attributes' => $this->decodeJsonField($request->input('attributes')),
            'seo_keywords' => $this->decodeJsonField($request->input('seo_keywords')),
            'remove_images' => $this->decodeJsonField($request->input('remove_images')),
            'variations' => $this->decodeJsonField($request->input('variations')),
        ]);
    }

    private function normalizeBoolean($value): ?bool
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_bool($value)) {
            return $value;
        }

        if (in_array($value, [1, '1', 'true', 'on'], true)) {
            return true;
        }

        if (in_array($value, [0, '0', 'false', 'off'], true)) {
            return false;
        }

        return null;
    }

    private function normalizeNullableId($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        if (is_array($value)) {
            $selectedKey = array_key_first(array_filter($value));

            return is_numeric($selectedKey) ? (int) $selectedKey : null;
        }

        return null;
    }

    private function decodeJsonField($value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_array($value)) {
            return $value;
        }

        if (!is_string($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }
}
