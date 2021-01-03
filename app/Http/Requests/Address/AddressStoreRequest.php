<?php

namespace App\Http\Requests\Address;


use Facades\Tymon\JWTAuth\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required|string",
            "address_1" => "required|string|max:255",
            "address_2" => "sometimes|nullable|string",
            "city" => "required",
            "postal_code" => "required|string",
            "country_id" => "required|exists:countries,id",
            "default" => "boolean"

        ];
    }
}
