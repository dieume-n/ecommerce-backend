<?php

namespace Tests\Feature\Http\Controllers\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartStoreTest extends TestCase
{
    /** @test */
    public function store_add_product_to_the_users_cart()
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

    /** @test */
    public function store_fails_for_unauthenticated_users()
    {
        $this->json('POST', 'api/cart')
            ->assertStatus(401);
    }

    /** @test */
    public function store_fails_for_not_sending_the_products()
    {
        $user = User::factory()->create();

        $this->jsonAS($user, 'POST', 'api/cart')
            ->assertJsonValidationErrors(['products']);
    }

    /** @test */
    public function store_fails_for_products_not_being_an_array()
    {
        $user = User::factory()->create();

        $this->jsonAS($user, 'POST', 'api/cart', [
            'products' => 1
        ])
            ->assertJsonValidationErrors(['products']);
    }

    /** @test */
    public function store_fails_for_product_not_having_an_id()
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
    public function store_fails_for_non_existant_products()
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
    public function store_fails_for_product_quantity_not_being_numeric()
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
    public function store_fails_for_product_quantity_less_than_1()
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
}
