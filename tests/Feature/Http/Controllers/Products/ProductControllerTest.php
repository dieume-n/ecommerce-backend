<?php

namespace Tests\Feature\Http\Controllers\Products;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    /** @test */
    public function it_show_a_collection_of_products()
    {
        $products = Product::factory(2)->create();

        $response = $this->json('GET', 'api/products');

        $products->each(function ($product) use ($response) {
            $response->assertJsonFragment([
                'name' => $product->name
            ]);
        });
    }

    /** @test */
    public function it_has_pagination_data()
    {
        $this->json('GET', 'api/products')
            ->assertJsonStructure([
                'links',
                'meta'
            ]);
    }

    /** @test */
    public function it_shows_a_product()
    {
        $product = Product::factory()->create();
        $this->json('GET', route('products.show', $product->slug))
            ->assertJsonFragment([
                'name' => $product->name
            ]);
    }

    /** @test */
    public function it_fail_if_the_product_cannot_be_found()
    {
        $this->json('GET', route('products.show', 'nope'))
            ->assertStatus(404);
    }
}
