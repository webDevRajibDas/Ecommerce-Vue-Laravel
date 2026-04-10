<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'variation_types',
        'size_options',
        'color_options',
        'material_options',
        'pattern_options',
    ];

    protected $casts = [
        'variation_types' => 'array',
        'size_options' => 'array',
        'color_options' => 'array',
        'material_options' => 'array',
        'pattern_options' => 'array',
    ];

    /**
     * Get the product that owns the variation.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all available variation types.
     */
    public function getVariationTypesAttribute(): array
    {
        return $this->variation_types ?? [];
    }

    /**
     * Get all size options.
     */
    public function getSizeOptionsAttribute(): array
    {
        return $this->size_options ?? [];
    }

    /**
     * Get all color options.
     */
    public function getColorOptionsAttribute(): array
    {
        return $this->color_options ?? [];
    }

    /**
     * Get all material options.
     */
    public function getMaterialOptionsAttribute(): array
    {
        return $this->material_options ?? [];
    }

    /**
     * Get all pattern options.
     */
    public function getPatternOptionsAttribute(): array
    {
        return $this->pattern_options ?? [];
    }
}