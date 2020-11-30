<?php

namespace Tests\Feature\Http\Controllers\Categories;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    /** @test */
    public function it_returns_a_collection_of_categories()
    {
        $categories = Category::factory(2)->create();
        $response = $this->json('GET', 'api/categories');

        $categories->each(function ($category) use ($response) {
            $response->assertJsonFragment([
                'slug' => $category->slug
            ]);
        });
    }

    /** @test */
    public function it_returns_only_parents_categories()
    {
        $category = Category::factory()->create();

        $category->children()->save(
            Category::factory()->create()
        );

        $this->json('GET', 'api/categories')
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_returns_categories_ordered_by_their_given_order()
    {
        $category = Category::factory()->create(['order' => 2]);
        $anotherCategory = Category::factory()->create(['order' => 1]);

        $this->json('GET', 'api/categories')
            ->assertSeeInOrder([
                $anotherCategory->slug,
                $category->slug,
            ]);
    }
}
