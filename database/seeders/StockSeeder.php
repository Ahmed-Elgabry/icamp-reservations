<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stock;
use Faker\Factory as Faker;


class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            Stock::create([
                'name' => $faker->word,
                'description' => $faker->sentence,
                'image' => null,
                'price' => $faker->randomFloat(2, 5, 100), // Prices between 5 and 100
                'quantity' => $faker->numberBetween(10, 100)
            ]);
        }
    }
}
