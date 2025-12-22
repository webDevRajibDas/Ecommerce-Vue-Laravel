<?php

namespace App\Http\Controllers\Admin;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
     public function index(Request $request)
    {

         $query = Category::withCount('products');
        
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
        $categories = $query->paginate($request->get('perPage', 10))
            ->withQueryString();
        
        return Inertia::render('Categories/Index', [
            'categories' => $categories,
            'filters' => $request->only(['search', 'sortField', 'sortOrder']),
            'totalCategories' => Category::count(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'color' => 'nullable|string|max:7',
        ]);

        $category = Category::create($validated);

        return redirect()->route('categoriesList')
            ->with('success', 'Category created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
           
        ]);

        $category->update($validated);

        return redirect()->route('categoriesList')
            ->with('success', 'Category updated successfully');
    }

   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->exists()) {
            return redirect()->route('categoriesList')
                ->with('error', 'Cannot delete category that has products. Please reassign products first.');
        }

        $category->delete();

        return redirect()->route('categoriesList')
            ->with('success', 'Category deleted successfully');
    }
   /**
     * Remove multiple categories
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
            return redirect()->route('categoriesList')
                ->with('error', 'Cannot delete categories that have products. Please reassign products first.');
        }

        Category::whereIn('id', $request->ids)->delete();

        return redirect()->route('categoriesList')
            ->with('success', 'Selected categories deleted successfully');
    }
}
