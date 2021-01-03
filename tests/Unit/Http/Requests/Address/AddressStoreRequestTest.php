<?php

namespace Tests\Unit\Http\Requests\Address;


use Tests\TestCase;
use App\Models\User;
use App\Http\Requests\Address\AddressStoreRequest;

class AddressStoreRequestTest extends TestCase
{
    /** @test */
    public function authorize_returns_true()
    {
        $subject = new AddressStoreRequest();

        $this->assertTrue($subject->authorize());
    }

    /** @test */
    public function rules()
    {
        $subject = new AddressStoreRequest();
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->assertSame([
            "name" => "required|string",
            "address_1" => "required|string|max:255",
            "address_2" => "sometimes|nullable|string",
            "city" => "required",
            "postal_code" => "required|string",
            "country_id" => "required|exists:countries,id",
            "default" => "boolean"

        ], $subject->rules());
    }
}
