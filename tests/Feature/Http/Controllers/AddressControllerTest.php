<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use App\Http\Controllers\AddressController;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use App\Http\Requests\Address\AddressStoreRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressControllerTest extends TestCase
{
    use WithFaker, AdditionalAssertions;

    /** @test */
    public function it_fails_if_not_authenticated()
    {
        $this->json('GET', 'api/addresses')
            ->assertStatus(401);
    }

    /** @test */
    public function index_shows_users_addresses()
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id
        ]);

        $this->jsonAs($user, 'GET', 'api/addresses')
            ->assertJsonFragment([
                'name' => $address->name,
                'address_1' => $address->address_1
            ]);
    }

    /** @test */
    public function store_save_data_and_returns_its_json_representation()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/addresses', [
            'name' => $name = $this->faker->unique()->name,
            'address_1' => $address_1 = $this->faker->streetAddress,
            'city' => $city = $this->faker->city,
            'postal_code' => $postal_code = $this->faker->postcode,
            'country_id' => Country::factory()->create()->id
        ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => $name,
                'address_1' => $address_1,
                'city' => $city,
                'postal_code' => $postal_code,
            ]);
    }

    /** @test */
    public function store_uses_validation()
    {
        $this->assertActionUsesFormRequest(
            AddressController::class,
            'store',
            AddressStoreRequest::class
        );
    }
}
