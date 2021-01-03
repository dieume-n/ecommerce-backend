<?php

namespace Tests;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function jsonAs(JWTSubject $user, $method, $endpoint, $data = [], $headers = [])
    {
        $token = Auth::tokenById($user->id);
        return $this->json($method, $endpoint, $data, array_merge($headers, [
            'Authorization' => "Bearer " . $token
        ]));
    }

    public function actingAs($user, $driver = null)
    {
        $token = Auth::tokenById($user->id);
        $this->withHeader('Authorization', "Bearer {$token}");
        parent::actingAs($user);

        return $this;
    }
}
