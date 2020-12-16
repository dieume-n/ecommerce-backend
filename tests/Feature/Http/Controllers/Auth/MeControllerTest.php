<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MeControllerTest extends TestCase
{
    /** @test */
    public function it_fails_if_user_is_not_authenticated()
    {
        $this->json('GET', 'api/auth/me')
            ->assertStatus(401);
    }

    /** @test */
    public function it_returns_user_datails()
    {
        $user = User::factory()->create();
        $this->jsonAs($user, 'GET', 'api/auth/me')
            ->assertJsonFragment([
                'email' => $user->email
            ]);
    }
}
