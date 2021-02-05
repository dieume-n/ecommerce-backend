<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user) {
            Address::Factory()->create([
                'user_id' => $user->id,
                'country_id' => random_int(1, 46),
                "default" => true
            ]);
        });
    }
}
