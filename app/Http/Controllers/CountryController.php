<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Resources\CountryResource;

class CountryController extends Controller
{
    public function index()
    {
        return CountryResource::collection(
            Country::all()
        );
    }
}
