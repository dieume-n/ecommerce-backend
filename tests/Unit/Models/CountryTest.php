<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Country;
use App\Models\ShippingMethod;

class CountryTest extends TestCase
{
    /** @test */
    public function it_has_many_shipping_methods()
    {
        $country = Country::factory()->create();

        $country->shippingMethods()->attach(
            ShippingMethod::factory()->create()
        );

        $this->assertInstanceOf(ShippingMethod::class, $country->shippingMethods->first());
    }
}
