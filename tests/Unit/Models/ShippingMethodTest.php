<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Country;
use App\Ecommerce\Money;
use App\Models\ShippingMethod;

class ShippingMethodTest extends TestCase
{
    /** @test */
    public function it_retuns_a_money_instance_for_the_price()
    {
        $shipping = ShippingMethod::factory()->create();

        $this->assertInstanceOf(Money::class, $shipping->price);
    }

    /** @test */
    public function it_retuns_a_formatted_price()
    {
        $shipping = ShippingMethod::factory()->create([
            'price' => 2000
        ]);

        $this->assertEquals("$20.00", $shipping->formattedPrice);
    }

    /** @test */
    public function it_belongs_to_many_countries()
    {
        $shipping = ShippingMethod::factory()->create([
            'price' => 2000
        ]);

        $shipping->countries()->attach(
            $country = Country::factory()->create()
        );

        $this->assertInstanceOf(Country::class, $shipping->countries->first());
    }
}
