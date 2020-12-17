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
}
