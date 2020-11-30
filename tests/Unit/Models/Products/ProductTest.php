<?php

namespace Tests\Unit\Models\Products;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /** @test */
    public function it_uses_the_slug_for_the_route_key_name()
    {
        $product = new Product();
        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }
}
