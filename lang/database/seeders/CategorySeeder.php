<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => json_encode(['ar' => ' غسيل مباشر', 'en' => 'Direct Wash']),
                'desc' => json_encode(['ar' => ' غسيل مباشر', 'en' => 'Direct Wash']),
                'image' => 'dashboard/assets/media/avatars/300-1.jpg',
                'is_active' => 1
            ],
            [
                'name' => json_encode(['ar' => ' غسيل مع تعقييم', 'en' => 'Washing with sterilization']),
                'desc' => json_encode(['ar' => '  غسيل مع تعقييم', 'en' => 'Washing with sterilization']),
                'image' => 'dashboard/assets/media/avatars/300-1.jpg',
                'is_active' => 1
            ],
            [
                'name' => json_encode(['ar' => ' غسيل مع تكييس', 'en' => 'Washing with bag']),
                'desc' => json_encode(['ar' => ' غسيل مع تكييس', 'en' => 'Washing with bag']),
                'image' => 'dashboard/assets/media/avatars/300-1.jpg',
                'is_active' => 1
            ]
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
