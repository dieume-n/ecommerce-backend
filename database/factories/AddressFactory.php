<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->name,
            'address_1' => $this->faker->streetAddress,
            'address_2' => $this->faker->secondaryAddress,
            'city' => $this->faker->city,
            'postal_code' => $this->faker->postcode,
            'country_id' => Country::factory()
        ];
    }
}
