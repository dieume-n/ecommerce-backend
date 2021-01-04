<?php

namespace App\Models;

use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function shippingMethods()
    {
        return $this->belongsToMany(ShippingMethod::class);
    }
}
