<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $bankAccount = Account::where('name', 'Bank Account')->first();
        $mobileMoney = Account::where('name', 'Mobile Money')->first();
        $cash = Account::where('name', 'Cash')->first();
        $foodCategory = Category::where('name', 'Food')->first();
        $fuelCategory = Category::where('name', 'Fuel')->first();
        $budget = Budget::first();


        Transaction::create([
            'user_id' => $user->id,
            'account_id' => $bankAccount->id,
            'category_id' => $foodCategory->id,
            'budget_id' => null,
            'type' => 'expense',
            'amount' => 50.00,
            'description' => 'Bought groceries',
            'transaction_date' => now(),
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'account_id' => $mobileMoney->id,
            'category_id' => $fuelCategory->id,
            'budget_id'=> null,
            'type' => 'expense',
            'amount' => 30.00,
            'description' => 'Refueled car',
            'transaction_date' => now(),
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'account_id' => $cash->id,
            'category_id' => null,
            'budget_id' => null,
            'type' => 'income',
            'amount' => 500.00,
            'description' => 'Received cash payment',
            'transaction_date' => now(),
        ]);
    }
}
