<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $foodCategory = Category::where('name', 'Food')->first();
        $transportCategory = Category::where('name', 'Transport')->first();
        $Account= Account::first();
        Budget::create([
            'user_id' => $user->id,
            'amount' => 1000.00,
            'category_id' => $foodCategory->id,
            'account_id'=> $Account->id,
            'start_date' => now()->startOfMonth(),
            'limit'=>10,
            'spent'=>900.00,
            'end_date' => now()->endOfMonth(),
            'exceeded' => false,
        ]);

        Budget::create([
            'user_id' => $user->id,
            'amount' => 500.00,
            'category_id' => $transportCategory->id,
            'account_id' => $Account->id,
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->endOfMonth(),
            'spent' => 500.00,
            'exceeded' => true,
        ]);

    }
}
