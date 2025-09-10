<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ['key' => 'app_name_ar', 'type' => 'text', 'value' => 'Icamp'],
            ['key' => 'app_name_en', 'type' => 'text', 'value' => 'icamp'],
            ['key' => 'firebase_key', 'type' => 'text', 'value' => 'AAAAi0Y_HnY:APA91bGeuHqUXsXiwWMDlJ-tenEOiKmRZ7pfifFPvI0XUzUiIRD6togg468docAR0gdTpY40Yvr50I8610Fdm9jG3RT-iYakNLthfVcxViBSJ6lIzt5gVh77Y_4VY3oqYyP64Svx6QxR'],
            ['key' => 'app_percentage', 'type' => 'number', 'value' => 10],
            ['key' => 'logo', 'type' => 'image', 'value' => 'dashboard/assets/media/avatars/300-1.jpg'],
            ['key' => 'about_ar', 'type' => 'textarea', 'value' => 'Icamp'],
            ['key' => 'about_en', 'type' => 'textarea', 'value' => 'icamp'],
            ['key' => 'terms_ar', 'type' => 'textarea', 'value' => 'Icamp'],
            ['key' => 'terms_en', 'type' => 'textarea', 'value' => 'icamp'],
            ['key' => 'policy_ar', 'type' => 'textarea', 'value' => 'Icamp'],
            ['key' => 'policy_en', 'type' => 'textarea', 'value' => 'icamp'],
            ['key' => 'facebook', 'type' => 'link', 'value' => 'https://www.facebook.com/'],
            ['key' => 'twitter', 'type' => 'link', 'value' => 'https://www.twitter.com/'],
            ['key' => 'instagram', 'type' => 'link', 'value' => 'https://www.instagram.com/'],
            ['key' => 'slogan_en', 'type' => 'text', 'value' => 'icamp'],
            ['key' => 'slogan_ar', 'type' => 'text', 'value' => 'Icamp'],
            ['key' => 'email', 'type' => 'text', 'value' => 'admin@gmail.com'],
            ['key' => 'phone', 'type' => 'text', 'value' => '1346546464'],
            ['key' => 'address', 'type' => 'text', 'value' => 'العنوان'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
