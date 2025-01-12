<?php

use App\Models\User;
use App\Notifications\BudgetLimitNotification;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $users = User::with('budgets')->get();
    foreach ($users as $user) {
        foreach ($user->budgets as $budget) {
            $existingNotification = $user->unreadNotifications()
                ->where('data->budget_name', $budget->category?->name)
                ->first();

            if (!$existingNotification && $budget->amount <= $budget->limit) {
                $user->notify(new BudgetLimitNotification($budget->category?->name, $budget->amount, $budget->limit));
            }
        }
    }
})->everyFiveSeconds();
