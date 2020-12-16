<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SignupControllerTest extends TestCase
{
    /** @test */
    public function it_requires_a_name()
    {
        $this->json('POST', 'api/auth/signup')
            ->assertJsonValidationErrors(['name']);
    }
    /** @test */
    public function it_requires_an_email()
    {
        $this->json('POST', 'api/auth/signup')
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_requires_a_valid_email()
    {
        $this->json('POST', 'api/auth/signup', [
            'email' => 'abc'
        ])
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_requires_a_unique_email()
    {
        $user = User::factory()->create();

        $this->json('POST', 'api/auth/signup', [
            'email' => $user->email
        ])
            ->assertJsonValidationErrors(['email']);
    }
    /** @test */
    public function it_requires_a_password()
    {
        $this->json('POST', 'api/auth/signup')
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function it_registers_a_user()
    {
        $this->json('POST', 'api/auth/signup', [
            'name' => $name = 'John Doe',
            'email' => $email = 'john@example.com',
            'password' => 'secret123'
        ]);
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'name' => $name
        ]);
    }

    /** @test */
    public function it_returns_a_user_on_signup()
    {
        $this->json('POST', 'api/auth/signup', [
            'name' => $name = 'John Doe',
            'email' => $email = 'john@example.com',
            'password' => 'secret123'
        ])->assertJsonFragment([
            'email' => $email
        ]);
    }
}
