<?php

namespace Tests\Unit\Models\Products;

use Tests\TestCase;
use App\Models\Product;
use App\Ecommerce\Money;
use App\Models\Category;
use App\Models\ProductVariation;

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
        $product = Product::factory()->create();

        $product->categories()->save(
            Category::factory()->create()
        );

        $this->assertInstanceOf(Category::class, $product->categories->first());
    }

    /** @test */
    public function it_has_many_variations()
    {
        $this->withoutExceptionHandling();

        $product = Product::factory()->create();

        $product->variations()->save(
            ProductVariation::factory()->create()
        );

        $this->assertInstanceOf(ProductVariation::class, $product->variations->first());
    }

    /** @test */
    public function it_returns_a_money_instance_for_the_price()
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Money::class, $product->price);
    }

    /** @test */
    public function it_returns_a_formatted_price()
    {
        $product = Product::factory()->create([
            'price' => 1000
        ]);

        $this->assertEquals($product->formattedPrice, "$10.00");
    }
}
