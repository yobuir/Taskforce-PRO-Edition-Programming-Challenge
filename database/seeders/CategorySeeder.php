<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        $food = Category::create([
            'user_id' => $user->id,
            'name' => 'Food',
        ]);
        Category::create([
            'user_id' => $user->id,
            'name' => 'Groceries',
            'parent_id' => $food->id
        ]);
        Category::create([
            'user_id' => $user->id,
            'name' => 'Dining Out',
            'parent_id' => $food->id
        ]);

        $transport = Category::create([
            'user_id' => $user->id,
            'name' => 'Transport',
        ]);
        Category::create([
            'user_id' => $user->id,
            'name' => 'Fuel',
            'parent_id' => $transport->id
        ]);
        Category::create([
            'user_id' => $user->id,
            'name' => 'Public Transport',
            'parent_id' => $transport->id
        ]);

        Category::create([
            'user_id' => $user->id,
            'name' => 'Utilities'
        ]);
    }
}
