<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Database\Factories\CategoryFactory;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            "Coffee" => Str::slug("Coffee"),
            "Shoes" => Str::slug("Shoes")
        ];

        collect($categories)->each(function ($slug, $name) {
            Category::create([
                "name" => $name,
                "slug" => $slug
            ]);
        });
    }
}
