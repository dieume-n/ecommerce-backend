<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;

class AddressTest extends TestCase
{
    /** @test */
    public function it_has_a_country()
    {
        $address = Address::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->assertInstanceOf(Country::class, $address->country);
    }
    /** @test */
    public function it_sets_old_addresses_to_not_default()
    {
        $user = User::factory()->create();

        $oldAddress = Address::factory()->create([
            "default" => true,
            "user_id" => $user->id
        ]);

        $newAddress = Address::factory()->create([
            "default" => true,
            "user_id" => $user->id
        ]);

        $this->assertFalse($oldAddress->fresh()->default);
        $this->assertTrue($newAddress->fresh()->default);
    }
}
