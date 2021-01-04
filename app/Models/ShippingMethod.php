<?php

namespace App\Models;

use App\Models\Country;
use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingMethod extends Model
{
    use HasFactory, HasPrice;

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }
}
