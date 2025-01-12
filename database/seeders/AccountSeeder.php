<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::first();

        Account::create([
            'user_id' => $user->id,
            'name' => 'Bank Account',
            'type' => 'bank',
            'balance' => 5000.00
        ]);

        Account::create([
            'user_id' => $user->id,
            'name' => 'Mobile Money',
            'type' => 'mobile_money',
            'balance' => 2000.00
        ]);

        Account::create([
            'user_id' => $user->id,
            'name' => 'Cash',
            'type' => 'cash',
            'balance' => 1000.00
        ]);
    }
}
