<?php

namespace App\Models;

use App\Models\Traits\HasPrice;
use App\Models\ProductVariation;
use App\Models\Traits\CanBeScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, CanBeScoped, HasPrice;

    public function getRouteKeyName()
    {
        return 'slug';
    }



    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }



    public function variations()
    {
        return $this->hasMany(ProductVariation::class)->orderBy('order', 'asc');
    }
}
