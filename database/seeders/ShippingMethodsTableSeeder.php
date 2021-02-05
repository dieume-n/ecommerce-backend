<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shippingMethods = [
            "UPS" => 1000,
            "FedX" => 1500,
            "Royal Mail 1st class" => 1000,
            "Rpyal Mail 2nd class" => 500
        ];

        collect($shippingMethods)->each(function ($price, $name) {
            ShippingMethod::create([
                "name" => $name,
                "price" => $price
            ]);
        });
    }
}
