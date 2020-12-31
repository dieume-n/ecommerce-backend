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
    public function it_belongs_to_a_user()
    {
        $address = Address::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->assertInstanceOf(User::class, $address->user);
    }
}
