<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
       'name',
        'slug',
        'description',
        'body',
        'price',
        'sale_price',
        'cost_price',
        'sku',
        'quantity',
        'track_quantity',
        'low_stock_threshold',
        'barcode',
        'weight',
        'length',
        'width',
        'height',
        'category_id',
        'brand_id',
        'is_visible',
        'is_featured',
        'type',
        'tags',
        'attributes',
        'warranty',
        'video_url',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'published_at',
    ];

   protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'quantity' => 'integer',
        'track_quantity' => 'boolean',
        'low_stock_threshold' => 'integer',
        'weight' => 'decimal:3',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'is_visible' => 'boolean',
        'is_featured' => 'boolean',
        'tags' => 'array',
        'attributes' => 'array',
        'seo_keywords' => 'array',
        'published_at' => 'datetime',
    ];


     // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

  

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }



    public function getGalleryImagesAttribute()
    {
        return $this->images()->where('is_main', false)->get()->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => Storage::url($image->path),
                'order' => $image->order,
            ];
        });
    }

      // Accessors
    public function getMainImageUrlAttribute()
    {
        if ($this->mainImage) {
            return Storage::url($this->mainImage->path);
        }
        return null;
    }


     // Helper methods
    public function isVariable()
    {
        return $this->type === 'variable';
    }

    public function getVariationOptions($type)
    {
        if (!$this->isVariable() || !$this->variations->first()) {
            return [];
        }

        $variation = $this->variations->first();
        
        switch ($type) {
            case 'size':
                return $variation->size_options ?? [];
            case 'color':
                return $variation->color_options ?? [];
            case 'material':
                return $variation->material_options ?? [];
            case 'pattern':
                return $variation->pattern_options ?? [];
            default:
                return [];
        }
    }
}
