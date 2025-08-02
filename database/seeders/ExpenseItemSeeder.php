<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExpenseItem;

class ExpenseItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expenseItems = [
            ['name' => 'إيجار', 'description' => 'مصاريف الإيجار الشهرية'],
            ['name' => 'رواتب', 'description' => 'رواتب الموظفين الشهرية'],
            ['name' => 'كهرباء', 'description' => 'فاتورة الكهرباء'],
            ['name' => 'ماء', 'description' => 'فاتورة الماء'],
            ['name' => 'إنترنت', 'description' => 'فاتورة الإنترنت'],
            ['name' => 'مستلزمات مكتبية', 'description' => 'مصاريف المستلزمات المكتبية'],
            ['name' => 'صيانة', 'description' => 'مصاريف الصيانة'],
            ['name' => 'نقل', 'description' => 'مصاريف النقل والمواصلات'],
        ];

        foreach ($expenseItems as $item) {
            ExpenseItem::create($item);
        }
    }
}
