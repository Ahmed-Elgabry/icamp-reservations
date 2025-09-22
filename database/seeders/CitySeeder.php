<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            [
                'name' => json_encode(['ar' => 'مكة', 'en' => 'Makkah']),
                'image' => null
            ],
            [
                'name' => json_encode(['ar' => 'مصر', 'en' => 'Eqypt']),
                'image' => null
            ],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->updateOrInsert(
                ['name' => $city['name']],
                $city
            );
        }
    }
}
