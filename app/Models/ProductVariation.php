<?php

namespace App\Models;

use App\Models\Stock;
use App\Models\Product;
use App\Ecommerce\Money;
use App\Models\Traits\HasPrice;
use App\Models\ProductVariationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariation extends Model
{
    use HasFactory, HasPrice;

    public function getPriceAttribute($value)
    {
        if ($value === null) return $this->product->price;
        return new Money($value);
    }

    public function priceVaries()
    {
        return $this->price->amount() !== $this->product->price->amount();
    }

    public function inStock()
    {
        return (bool) $this->stock->first()->pivot->in_stock;
    }

    public function stockCount()
    {
        return $this->stock->sum('pivot.stock');
    }

    public function minStock($count)
    {
        return min($this->stockCount(), $count);
    }

    public function type()
    {
        return $this->hasOne(ProductVariationType::class, 'id', 'product_variation_type_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function stock()
    {
        return $this->belongsToMany(
            Productvariation::class,
            'products_variation_stock_view',
            'product_variation_id'
        )
            ->withPivot([
                'stock',
                'in_stock'
            ]);
    }
}
