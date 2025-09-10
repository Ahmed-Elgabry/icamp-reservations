<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class BankAccounts extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = [
            [
                'name' => "QNB",
                'balance' => 500,
                'account_number' => 123,
            ],
            [
                'name' => "AUCH",
                'balance' => 500,
                'account_number' => 1232,
            ]
        ];

        foreach ($accounts as $account) {
            BankAccount::updateOrCreate(
                ['account_number' => $account['account_number']],
                $account
            );
        }
    }
}
