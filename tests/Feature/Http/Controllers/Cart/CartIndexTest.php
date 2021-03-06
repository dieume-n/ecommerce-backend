<?php

namespace Tests\Feature\Http\Controllers\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartIndexTest extends TestCase
{
    /** @test */
    public function index_fails_for_unauthenticated_user()
    {
        $this->json('GET', 'api/cart')
            ->assertStatus(401);
    }

    /** @test */
    public function index_shows_products_in_the_users_cart()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = ProductVariation::factory()->create()
        );

        $this->jsonAs($user, 'GET', 'api/cart')
            ->assertJsonFragment([
                'id' => $product->id
            ]);
    }

    /** @test */
    public function index_shows_if_the_user_cart_is_empty()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'GET', 'api/cart')
            ->assertJsonFragment([
                'empty' => true
            ]);
    }

    /** @test */
    public function index_shows_a_formatted_subtotal()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'GET', 'api/cart')
            ->assertJsonFragment([
                'subTotal' => "$0.00"
            ]);
    }

    /** @test */
    public function index_shows_a_formatted_total()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'GET', 'api/cart')
            ->assertJsonFragment([
                'total' => "$0.00"
            ]);
    }

    /** @test */
    public function index_syncs_the_cart()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => 2
            ]
        );

        $this->jsonAs($user, 'GET', 'api/cart')
            ->assertJsonFragment([
                'changed' => true
            ]);
    }
}
