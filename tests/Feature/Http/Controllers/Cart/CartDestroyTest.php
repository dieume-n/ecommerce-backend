<?php

namespace Tests\Feature\Http\Controllers\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartDestroyTest extends TestCase
{
    /** @test */
    public function destroy_fails_for_unauthenticated_users()
    {
        $this->json('DELETE', 'api/cart/1')
            ->assertStatus(401);
    }

    /** @test */
    public function destroy_fails_for_invalid_product()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'DELETE', 'api/cart/1')
            ->assertStatus(404);
    }

    /** @test */
    public function destroy_removes_product_from_the_cart()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = ProductVariation::factory()->create()
        );

        $this->jsonAS($user, 'DELETE', "api/cart/{$product->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('cart_user', [
            'product_variation_id' => $product->id
        ]);
    }
}
