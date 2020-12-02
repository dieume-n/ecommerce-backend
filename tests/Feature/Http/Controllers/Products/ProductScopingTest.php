<?php

namespace Tests\Feature\Http\Controllers\Products;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductScopingTest extends TestCase
{
    /** @test */
    public function it_can_scope_by_category()
    {
        $product = Product::factory()->create();

        $product->categories()->save(
            $category = Category::factory()->create()
        );

        $anotherProduct = Product::factory()->create();

        $this->json('GET', "api/products?category={$category->slug}")
            ->assertJsonCount(1, 'data');
    }
}
