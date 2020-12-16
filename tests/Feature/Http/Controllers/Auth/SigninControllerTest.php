<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SigninControllerTest extends TestCase
{
    /** @test */
    public function it_requires_an_email()
    {
        $this->json('POST', 'api/auth/signin')
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_requires_a_valid_email()
    {
        $this->json('POST', 'api/auth/signin', [
            'email' => 'abc'
        ])
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_requires_a_password()
    {
        $this->json('POST', 'api/auth/signin')
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function it_returns_an_error_when_bad_credentials_are_provided()
    {
        $user = User::factory()->create();

        $this->json('POST', 'api/auth/signin', [
            'email' => $user->email,
            'password' => 'secret'
        ])
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_returns_a_token_when_credentials_match()
    {
        $user = User::factory()->create();

        $this->json('POST', 'api/auth/signin', [
            'email' => $user->email,
            'password' => 'password'
        ])
            ->assertJsonStructure([
                'meta' => [
                    'token'
                ]
            ]);
    }

    /** @test */
    public function it_returns_a_user_when_credentials_match()
    {
        $user = User::factory()->create();

        $this->json('POST', 'api/auth/signin', [
            'email' => $user->email,
            'password' => 'password'
        ])
            ->assertJsonFragment([
                'email' => $user->email,
                'name' => $user->name
            ]);
    }
}
