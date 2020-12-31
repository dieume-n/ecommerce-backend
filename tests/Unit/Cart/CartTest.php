<?php

namespace Tests\Unit\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Ecommerce\Cart;
use App\Ecommerce\Money;
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

    /** @test */
    public function it_can_delete_product_from_the_cart()
    {
        $product = ProductVariation::factory()->create();

        $cart = new Cart(
            $user = User::factory()->create()
        );
        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $cart->delete($product->id);

        $this->assertCount(0, $user->fresh()->cart);
    }

    /** @test */
    public function it_can_empty_the_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create()
        );

        $cart->empty();

        $this->assertCount(0, $user->fresh()->cart);
    }

    /** @test */
    public function it_can_check_if_the_cart_is_empty_of_quantities()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => 0
            ]
        );

        $this->assertTrue($cart->isEmpty());
    }

    /** @test */
    public function it_returns_a_money_instance_for_the_subtotal()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $this->assertInstanceOf(Money::class, $cart->subTotal());
    }

    /** @test */
    public function it_returns_the_correct_subtotal_amount()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create([
                'price' => 1000
            ]),
            [
                'quantity' => 3
            ]
        );

        $this->assertEquals($cart->subTotal()->amount(), 3000);
    }

    /** @test */
    public function it_returns_a_money_instance_for_the_total()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $this->assertInstanceOf(Money::class, $cart->total());
    }

    /** @test */
    public function it_syncs_the_cart_to_update_quantities()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => 2
            ]
        );

        $cart->sync();

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 0);
    }

    /** @test */
    public function it_can_check_if_the_cart_has_changed_after_syncing()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => 2
            ]
        );

        $cart->sync();

        $this->assertTrue($cart->hasChanged());
    }
}
