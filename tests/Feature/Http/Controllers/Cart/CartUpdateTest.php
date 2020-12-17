<?php

namespace Tests\Feature\Http\Controllers\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartUpdateTest extends TestCase
{
    /** @test */
    public function update_fails_for_unauthenticated_users()
    {
        $this->json('PATCH', 'api/cart/1')
            ->assertStatus(401);
    }

    /** @test */
    public function update_fails_if_product_cannot_be_found()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'PATCH', 'api/cart/1')
            ->assertStatus(404);
    }

    /** @test */
    public function update_fails_for_not_sending_quantity()
    {
        $user = User::factory()->create();

        $product = ProductVariation::factory()->create();

        $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}")
            ->assertJsonValidationErrors(['quantity']);
    }

    /** @test */
    public function update_fails_for_non_numeric_quantity()
    {
        $user = User::factory()->create();

        $product = ProductVariation::factory()->create();

        $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}", [
            'quantity' => "one"
        ])
            ->assertJsonValidationErrors(['quantity']);
    }

    /** @test */
    public function update_fails_for_quantity_less_than_one()
    {
        $user = User::factory()->create();

        $product = ProductVariation::factory()->create();

        $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}", [
            'quantity' => 0
        ])
            ->assertJsonValidationErrors(['quantity']);
    }

    /** @test */
    public function update_changes_the_product_quantity()
    {
        $user = User::factory()->create();

        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => 1
            ]
        );

        $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}", [
            'quantity' =>  $quantity = 5
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => $quantity
        ]);
        $this->assertEquals($quantity, $user->fresh()->cart->first()->pivot->quantity);
    }
}
