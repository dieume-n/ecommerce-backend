<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartControllerTest extends TestCase
{
    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $this->json('POST', 'api/cart')
            ->assertStatus(401);
    }

    /** @test */
    public function it_requires_products()
    {
        $user = User::factory()->create();

        $this->jsonAS($user, 'POST', 'api/cart')
            ->assertJsonValidationErrors(['products']);
    }

    /** @test */
    public function it_requires_products_to_be_an_array()
    {
        $user = User::factory()->create();

        $this->jsonAS($user, 'POST', 'api/cart', [
            'products' => 1
        ])
            ->assertJsonValidationErrors(['products']);
    }

    /** @test */
    public function it_requires_products_to_have_an_id()
    {
        $user = User::factory()->create();

        $this->jsonAS($user, 'POST', 'api/cart', [
            'products' => [
                ['quantity' => 1]
            ]
        ])
            ->assertJsonValidationErrors(['products.0.id']);
    }

    /** @test */
    public function it_requires_products_to_exists()
    {
        $user = User::factory()->create();

        $this->jsonAS($user, 'POST', 'api/cart', [
            'products' => [
                [
                    'id' =>  1,
                    'quantity' => 1
                ]
            ]
        ])
            ->assertJsonValidationErrors(['products.0.id']);
    }

    /** @test */
    public function it_requires_products_quantity_to_be_numeric()
    {
        $user = User::factory()->create();

        $this->jsonAS($user, 'POST', 'api/cart', [
            'products' => [
                [
                    'id' =>  1,
                    'quantity' => "one"
                ]
            ]
        ])
            ->assertJsonValidationErrors(['products.0.quantity']);
    }

    /** @test */
    public function it_requires_products_quantity_to_be_at_least_one()
    {
        $user = User::factory()->create();

        $this->jsonAS($user, 'POST', 'api/cart', [
            'products' => [
                [
                    'id' =>  1,
                    'quantity' => 0
                ]
            ]
        ])
            ->assertJsonValidationErrors(['products.0.quantity']);
    }

    /** @test */
    public function it_can_add_product_to_the_users_cart()
    {
        $user = User::factory()->create();

        $product = ProductVariation::factory()->create();

        $this->jsonAS($user, 'POST', 'api/cart', [
            'products' => [
                ['id' => $product->id, 'quantity' => 2]
            ]
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => 2
        ]);
    }
}
