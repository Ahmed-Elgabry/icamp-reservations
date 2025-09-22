<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(NewPermissionsSeeder::class);
        $this->call(MissingPermissionsSeeder::class);
        $this->call(SmsSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(StockSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(ExpenseItemSeeder::class);
        $this->call(BankAccounts::class);
        $this->call(OrderSeeder::class);
        $this->call(SurveySeeder::class);
        $this->call(WhatsappMessageTemplateSeeder::class);
        $this->call(PaymentReminderTemplateSeeder::class);
        $this->call(ManualTemplateSeeder::class);
    }
}
