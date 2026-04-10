<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'parent_id',
        'position',
        'icon',
        'badge',
        'product_count'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $slug = Str::slug($category->name);
            $count = Category::where('slug', 'like', "{$slug}%")->count();
            $category->slug = $count ? "{$slug}-{$count}" : $slug;
        });

        static::updating(function ($category) {
            $slug = Str::slug($category->name);
            $count = Category::where('slug', 'like', "{$slug}%")->where('id', '!=', $category->id)->count();
            $category->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }

    public function parent()
    {

        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the products for the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
