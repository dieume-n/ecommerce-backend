<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressControllerTest extends TestCase
{
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
}
