<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\ProductVariation;

class UserTest extends TestCase
{
    /** @test */
    public function it_has_many_cart_products()
    {
        $user = User::factory()->create();
        $user->cart()->attach(
            ProductVariation::factory()->create()
        );

        $this->assertInstanceOf(ProductVariation::class, $user->cart->first());
    }

    /** @test */
    public function it_has_a_quantity_for_each_cart_product()
    {
        $user = User::factory()->create();
        $user->cart()->attach(
            ProductVariation::factory()->create(),
            [
                'quantity' => $quantity = 5
            ]
        );

        $this->assertEquals($quantity, $user->cart->first()->pivot->quantity);
    }

    /** @test */
    public function it_has_many_addresses()
    {
        $user = User::factory()->create();

        $user->addresses()->save(
            Address::factory()->make()
        );

        $this->assertInstanceOf(Address::class, $user->addresses()->first());
    }
}
