<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Account;
use App\Models\Transaction; // Add this import

new class extends Component {
    use WithPagination;

    public function with(): array
    {

        $transactions = Transaction::where('user_id', auth()->id())->get();
        $expenses = $transactions->where('type', 'expense')->pluck('amount');
        $incomes = $transactions->where('type', 'income')->pluck('amount');
        $labels = $transactions->pluck('created_at')->map(function ($date) {
            return $date->format('Y-m-d');
        });
        return [
            'accounts' => Account::where('user_id', auth()->id())->paginate(10),
            "transactions" => Transaction::where('user_id', auth()->id())->get(),
            'expenses' => $expenses,
            'incomes' => $incomes,
            'labels' => $labels,
        ];
    }




}; ?>

<div class="lg:py-12 flex flex-col gap-3">
    <div class="flex justify-items-end justify-end">
        @include('layouts.includes.sub_menu.app')
    </div>
    <div>
        <hr />
    </div>

    <div class="mt-4 flex lg:flex-row flex-col gap-8 justify-between">
        <div>
            <h1 class="text-2xl  font-normal">Hi, {{ Auth::user()->name }} Welcome back !</h1>
        </div>
        <div class="flex gap-3 flex-wrap">
            @livewire('top-up-balance')
            @livewire('remove-expense')
        </div>
    </div>
    <div class="grid lg:grid-cols-3 grid-cols-1 gap-3 mt-6">
        <div
            class="border flex flex-col gap-3 bg-blue-500/10 border-b-4 border-blue-700  border-blue-500/20  rounded px-5 py-8 ">
            <div class="flex items-center justify-between">
                <h5 class="font-bold text-xl text-blue-700">
                    <span>
                        Available Balance
                    </span>
                </h5>
            </div>

            <div class="flex gap-1 flex-col">
                <span>{{ Number::currency(auth()->user()->accounts()->sum('balance') ?? 0, auth()?->user()?->currency) }}</span>
                <span class="text-sm">Income: +
                    {{ Number::currency(auth()->user()->transactions()->where('transactions.type', 'income')->sum('amount') ?? 0, auth()?->user()?->currency) }}</span>
            </div>
        </div>
        <div class="border flex flex-col gap-3 bg-green-500/10 border-b-4 border-green-700/20  rounded px-5 py-8 ">
            <h5 class="font-bold text-xl text-green-700">Total Budget</h5>
            <span>{{ Number::currency(auth()->user()->budgets()->sum('amount') ?? 0, auth()?->user()?->currency) }}</span>
        </div>

        <div class="border flex flex-col gap-3 bg-red-500/10 border-b-4  border-red-700/20  rounded px-5 py-8 ">
            <h5 class="font-bold text-xl text-red-700">Total expenses</h5>
            <span>
                {{ Number::currency(auth()->user()->transactions()->where('transactions.type', 'expense')->sum('amount') ?? 0, auth()?->user()?->currency) }}
            </span>
        </div>
    </div>
    <div class="flex flex-wrap gap-3 mt-6">
        <div class="flex-fill w-[100%]">
            <h1 class="">
                Transactions chart
            </h1>
            <canvas id="myChart"></canvas>
        </div>
    </div>     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');

        var labels = @json($labels);
        var expenses = @json($expenses);
        var incomes = @json($incomes);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Expenses',
                        data: expenses,
                        borderWidth: 1,
                        borderColor: '#C2410C',
                        backgroundColor: '#C2410C',
                    },
                    {
                        label: 'Incomes',
                        data: incomes,
                        borderColor: '#2D7EF3',
                        backgroundColor: '#2D7EF3',
                    }
                ]
            },

            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</div>
