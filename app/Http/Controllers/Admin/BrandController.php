<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index(Request $request)
    {

        $query = Brand::withCount('products');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sortField = $request->get('sortField', 'created_at');
        $sortOrder = $request->get('sortOrder', 'desc');
        $query->orderBy($sortField, $sortOrder);

        // Pagination
        $brands = $query->paginate($request->get('perPage', 10))
            ->withQueryString();

        return Inertia::render('brand/Index', [
            'brands' => $brands,
            'filters' => $request->only(['search', 'sortField', 'sortOrder']),
            'totalBrands' => Brand::count(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $brand = Brand::create($validated);

        return redirect()->route('brandsList')
            ->with('success', 'Brand created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',

        ]);

        $brand->update($validated);

        return redirect()->route('brandsList')
            ->with('success', 'Brand updated successfully');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        // Check if brand has products
        if ($brand->products()->exists()) {
            return redirect()->route('brandsList')
                ->with('error', 'Cannot delete brand that has products. Please reassign products first.');
        }

        $brand->delete();

        return redirect()->route('brandsList')
            ->with('success', 'Brand deleted successfully');
    }
    /**
     * Remove multiple brands
     */
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id'
        ]);

        // Check if any category has products
        $categoriesWithProducts = Category::whereIn('id', $request->ids)
            ->whereHas('products')
            ->count();

        if ($categoriesWithProducts > 0) {
            return redirect()->route('brandsList')
                ->with('error', 'Cannot delete brands that have products. Please reassign products first.');
        }

        Brand::whereIn('id', $request->ids)->delete();

        return redirect()->route('brandsList')
            ->with('success', 'Selected categories deleted successfully');
    }
}
