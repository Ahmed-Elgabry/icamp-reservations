<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banner = [
            'name' => json_encode(['ar' => 'بانر تجريبي', 'en' => 'test banner']),
            'desc' => json_encode(['ar' => 'بانر تجريبي', 'en' => 'test banner']),
            'image' => 'dashboard/assets/media/avatars/300-1.jpg',
            'is_active' => 1
        ];

        DB::table('banners')->updateOrInsert(
            ['name' => $banner['name']],
            $banner
        );
    }
}
