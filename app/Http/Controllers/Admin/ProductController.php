<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
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
            ->select('id', 'name', 'description', 'body','price','category_id','brand_id','sku','image','quantity')
            ->when($search, fn($query, $search) => $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            )
            ->orderBy('id', 'desc')
            ->paginate(50)
            ->withQueryString();
        //dd($products->toArray());
        return Inertia::render('products/Products', [
            'productsData' => $products,
            'filters' => ['search' => $search],
            'totalProducts' => Product::count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // Store product
    public function store(Request $request)
    {
        //dd($request->all());

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'description' => 'nullable|string',
            'body' => 'nullable|string',
            
            // Pricing
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
           
            // Inventory
            'sku' => 'nullable|string|unique:products,sku',
            'quantity' => 'required|integer|min:0',
            'track_quantity' => 'boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'stock_alert_email' => 'nullable|email',
            'barcode' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            
            // Relations
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            
            // Status
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
            'type' => 'required|in:simple,variable',
            
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
            'main_image' => 'required|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            
            // Variations
            'variations' => 'nullable|array',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'body' => $validated['body'],
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'],
                'cost_price' => $validated['cost_price'],
                'sku' => $validated['sku'],
                'quantity' => $validated['quantity'],
                'track_quantity' => $validated['track_quantity'],
                'low_stock_threshold' => $validated['low_stock_threshold'],
                'barcode' => $validated['barcode'],
                'weight' => $validated['weight'],
                'length' => $validated['length'],
               
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'is_visible' => $validated['is_visible'],
                'is_featured' => $validated['is_featured'],
                'type' => $validated['type'],
                'tags' => $validated['tags'] ? json_encode($validated['tags']) : null,
                'attributes' => $validated['attributes'] ? json_encode($validated['attributes']) : null,
                'warranty' => $validated['warranty'],
                'video_url' => $validated['video_url'],
                'seo_title' => $validated['seo_title'],
                'seo_description' => $validated['seo_description'],
                'seo_keywords' => $validated['seo_keywords'] ? json_encode($validated['seo_keywords']) : null,
                'published_at' => $validated['published_at'] ?? now(),
            ]);

             DB::commit(); exit;
            
            // Store main image
            if ($request->hasFile('main_image')) {
                $mainImagePath = $request->file('main_image')->store('products/' . $product->id . '/main', 'public');
                
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
            if ($product->type === 'variable' && isset($validated['variations'])) {
                $variations = $validated['variations'];
                
                ProductVariation::create([
                    'product_id' => $product->id,
                    'variation_types' => json_encode($variations['selectedTypes'] ?? []),
                    'size_options' => json_encode($variations['options']['size'] ?? []),
                    'color_options' => json_encode($variations['options']['color'] ?? []),
                    'material_options' => json_encode($variations['options']['material'] ?? []),
                    'pattern_options' => json_encode($variations['options']['pattern'] ?? []),
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('products.index')->with('success', 'Product created successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Failed to create product: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
