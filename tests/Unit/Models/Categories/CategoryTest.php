<?php

namespace Tests\Unit\Models\Categories;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
// use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_many_childern()
    {
        $Category = Category::factory()->create();
        $Category->children()->save(
            Category::factory()->create()
        );

        $this->assertInstanceOf(Category::class, $Category->children->first());
        $this->assertInstanceOf(Collection::class, $Category->children);
    }

    /**
     * @test
     */
    public function it_can_fetch_only_parents()
    {
        $this->withExceptionHandling();

        $Category = Category::factory()->create();
        $Category->children()->save(
            Category::factory()->create()
        );

        $this->assertEquals(1, Category::parents()->count());
    }

    /**
     * @test
     */
    public function it_is_orderable_by_a_numbered_order()
    {
        $this->withExceptionHandling();

        $Category = Category::factory()->create(['order' => 2]);
        $anotherCategory = Category::factory()->create(['order' => 1]);

        $this->assertEquals($anotherCategory->name, Category::ordered()->first()->name);
    }

    /** @test */
    public function it_has_many_products()
    {
        $category = Category::factory()->create();

        $category->products()->save(
            Product::factory()->create()
        );

        $this->assertInstanceOf(Product::class, $category->products->first());
    }
}
