<?php

namespace Tests\Unit\Models\Products;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
// use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /** @test */
    public function it_uses_the_slug_for_the_route_key_name()
    {
        $product = new Product();
        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }

    /** @test */
    public function it_has_many_categories()
    {
        $this->withoutExceptionHandling();

        $product = Product::factory()->create();

        $product->categories()->save(
            Category::factory()->create()
        );

        $this->assertInstanceOf(Category::class, $product->categories->first());
    }
}
