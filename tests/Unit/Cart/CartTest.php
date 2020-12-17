<?php

namespace Tests\Unit\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Ecommerce\Cart;
use App\Models\ProductVariation;

class CartTest extends TestCase
{
    /** @test */
    public function it_can_add_product_to_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $product = ProductVariation::factory()->create();
        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $this->assertCount(1, $user->fresh()->cart);
    }

    /** @test */
    public function it_can_increment_quantity_when_adding_more_product()
    {
        $product = ProductVariation::factory()->create();

        $cart = new Cart(
            $user = User::factory()->create()
        );
        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $cart = new Cart($user->fresh());
        $cart->add([
            ['id' => $product->id, 'quantity' => 3]
        ]);

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 4);
    }

    /** @test */
    public function it_can_update_quantities_in_the_cart()
    {
        $product = ProductVariation::factory()->create();

        $cart = new Cart(
            $user = User::factory()->create()
        );
        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $cart->update($product->id, 3);

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 3);
    }
}
