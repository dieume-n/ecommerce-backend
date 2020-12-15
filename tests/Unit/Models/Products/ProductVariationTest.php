<?php

namespace Tests\Unit\Models\Products;

use Tests\TestCase;
// use PHPUnit\Framework\TestCase;
use App\Models\Stock;
use App\Models\Product;
use App\Ecommerce\Money;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;

class ProductVariationTest extends TestCase
{
    /** @test */
    public function it_has_one_variation_type()
    {
        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(ProductVariationType::class, $variation->type);
    }

    /** @test */
    public function it_belongs_to_a_product()
    {
        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(Product::class, $variation->product);
    }

    /** @test */
    public function it_returns_a_money_instance_for_the_price()
    {
        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(Money::class, $variation->price);
    }

    /** @test */
    public function it_returns_a_formatted_price()
    {
        $variation = ProductVariation::factory()->create([
            'price' => 1000
        ]);

        $this->assertEquals($variation->formattedPrice, "$10.00");
    }

    /** @test */
    public function it_returns_the_product_price_if_price_is_null()
    {
        $product = Product::factory()->create([
            'price' => 1000
        ]);

        $variation = ProductVariation::factory()->create([
            'price' => null,
            'product_id' => $product->id
        ]);

        $this->assertEquals($product->price->amount(), $variation->price->amount());
    }

    /** @test */
    public function it_can_check_if_the_variation_price_is_different_to_the_product()
    {
        $product = Product::factory()->create([
            'price' => 1000
        ]);

        $variation = ProductVariation::factory()->create([
            'price' => 2000,
            'product_id' => $product->id
        ]);

        $this->assertTrue($variation->priceVaries());
    }

    /** @test */
    public function it_has_many_stocks()
    {
        $variation = ProductVariation::factory()->create();
        $variation->stocks()->save(
            Stock::factory()->make()
        );

        $this->assertInstanceOf(Stock::class, $variation->stocks()->first());
    }

    /** @test */
    public function it_has_stock_information()
    {
        $variation = ProductVariation::factory()->create();
        $variation->stocks()->save(
            Stock::factory()->make()
        );

        $this->assertInstanceOf(ProductVariation::class, $variation->stock->first());
    }

    /** @test */
    public function it_has_stock_count_pivot_within_stock_information()
    {
        $variation = ProductVariation::factory()->create();
        $variation->stocks()->save(
            Stock::factory()->make([
                'quantity' => $quantity = 5
            ])
        );

        $this->assertIsNumeric($variation->stock->first()->pivot->stock);
        $this->assertEquals($variation->stock->first()->pivot->stock, $quantity);
    }

    /** @test */
    public function it_has_in_stock_pivot_within_stock_information()
    {
        $variation = ProductVariation::factory()->create();
        $variation->stocks()->save(
            Stock::factory()->make()
        );

        $this->assertIsBool($variation->stock->first()->pivot->in_stock);
        $this->assertTrue($variation->stock->first()->pivot->in_stock);
    }

    /** @test */
    public function it_can_check_if_its_in_stock()
    {
        $variation = ProductVariation::factory()->create();
        $variation->stocks()->save(
            Stock::factory()->make()
        );

        $this->assertIsBool($variation->inStock());
        $this->assertTrue($variation->inStock());
    }

    /** @test */
    public function it_can_get_the_stock_count()
    {
        $variation = ProductVariation::factory()->create();
        $variation->stocks()->save(
            Stock::factory()->make([
                'quantity' => $quantity = 5
            ])
        );

        $this->assertIsInt($variation->stockCount());
        $this->assertEquals($variation->stockCount(), $quantity);
    }
}
