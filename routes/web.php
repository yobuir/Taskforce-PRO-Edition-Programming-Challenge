<?php

use Illuminate\Support\Facades\Route;

use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->prefix('dashboard')->name('dashboard')->group(function () {

        Volt::route('/', 'myapp/dashboard')->name('');
        Volt::route('/accounts', 'myapp/accounts/accountmanager')->name('.accounts');
        Volt::route('/categories', 'myapp/categories/categorymanager')->name('.categories');
        Volt::route('/budgets', 'myapp/budgets/budgetmanager')->name('.budgets');
        Volt::route('/transactions', 'myapp/transactions/transactiontracker')->name('.transactions');

        // notifications route and function
        route::post('/notifications/mark-as-read', function () {
            try {
                auth()->user()->unreadNotifications->markAsRead();

                session()->flash('success', 'Notifications marked as read successfully!');
            } catch (\Throwable $th) {
                session()->flash('error', $th->getMessage());
            }
            return redirect()->back();
        })->name('.notifications.mark-as-read');
    });
});
