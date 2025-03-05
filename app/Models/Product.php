<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = ['name','brand_id', 'description', 'image','images'];


    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_categorie_id'); //product_categories
    }

}
