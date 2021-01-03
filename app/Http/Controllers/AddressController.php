<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Resources\AddressResource;
use App\Http\Requests\Address\AddressStoreRequest;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function index(Request $request)
    {
        return AddressResource::collection(
            $request->user()->addresses
        );
    }

    public function store(AddressStoreRequest $request)
    {
        $address = Address::make(
            $request->validated()
        );

        $request->user()->addresses()->save($address);

        return new AddressResource($address);
    }
}
