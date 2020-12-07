<?php

namespace Tests\Unit\Models\Products;

use Tests\TestCase;
// use PHPUnit\Framework\TestCase;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;

class ProductVariationTest extends TestCase
{
    /** @test */
    public function it_has_one_variation_type()
    {
        // $this->withoutExceptionHandling();

        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(ProductVariationType::class, $variation->type);
    }
}
