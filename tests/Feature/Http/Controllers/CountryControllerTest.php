<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Country;

class CountryControllerTest extends TestCase
{
    /** @test */
    public function index_retuns_countries()
    {
        $country = Country::factory()->create();

        $this->json('GET', 'api/countries')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $country->id,
                'name' => $country->name
            ]);
    }
}
