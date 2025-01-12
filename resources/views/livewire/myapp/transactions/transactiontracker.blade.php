<?php

use Livewire\Volt\Component;

new class extends Component {
    public $account_id;
    public $category_id;
    public $amount;
    public $type;
    public $description;
    public $transaction_date;
    public $filter_date_from;
    public $filter_date_to;
    public $filter_category;
    public $filter_budget;
    public $filter_account;
    public $filter_type;

    public function with(): array
    {
        return [
            'categories' => auth()->user()->categories,
            'accounts' => auth()->user()->accounts,
            'budgets' => auth()->user()->budgets,
            'transactions' => auth()
                ->user()
                ->transactions()
                ->when($this->filter_date_from && $this->filter_date_to, fn($query) => $query->whereBetween('transaction_date', [$this->filter_date_from, $this->filter_date_to]))
                ->when($this->filter_category, fn($query) => $query->where('category_id', $this->filter_category))
                ->when($this->filter_budget, fn($query) => $query->where('budget_id', $this->filter_budget))
                ->when($this->filter_account, fn($query) => $query->where('account_id', $this->filter_account))
                ->when($this->filter_type, fn($query) => $query->where('transactions.type', $this->filter_type))
                ->paginate(10),
        ];
    }

    public function clearFilters()
    {
        $this->filter_date_from = null;
        $this->filter_date_to = null;
        $this->filter_category = null;
        $this->filter_budget = null;
        $this->filter_account = null;
        $this->filter_type = null;
    }
}; ?>


<div class="lg:py-12 flex flex-col gap-3">
    <div class="flex justify-items-end justify-end">
        @include('layouts.includes.sub_menu.app')
    </div>
    <div>
        <hr />
    </div>

    <h1 class="text-2xl font-normal">Transaction history</h1>

    <div class="flex flex-wrap gap-4 mb-4">
        <div>
            <x-label>From date</x-label>
            <x-input type="date" wire:model.live="filter_date_from" class="border rounded px-3 py-2"
                placeholder="From Date" />
        </div>
        <div>
            <x-label>To date</x-label>
            <x-input type="date" wire:model.live="filter_date_to" class="border rounded px-3 py-2"
                placeholder="To Date" />
        </div>
        <div>
            <x-label>Filter by</x-label>
            <select wire:model="filter_category"
                class="border  px-3 py-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Category</option>
                @forelse ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @empty
                @endforelse
            </select>
        </div>
        <div>
            <x-label>Filter by</x-label>
            <select wire:model.live="filter_budget"
                class="border  px-3 py-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Budgets</option>
                @forelse ($budgets as $budget)
                    <option value="{{ $budget->id }}">{{ $budget?->category?->name }}</option>
                @empty
                @endforelse
            </select>
        </div>
        <div>
            <x-label>Filter by</x-label> <select wire:model.live="filter_account"
                class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2">
                <option value="">Accounts</option>
                @forelse ($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @empty
                @endforelse
            </select>

        </div>
        <div>
            <x-label>Filter by</x-label>
            <select wire:model.live="filter_type"
                class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"">
                <option value="">Type</option>
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>
        </div>
        <div class="flex gap-3">
            <x-secondary-button wire:click="clearFilters" class="mt-5">Clear Filters</x-secondary-button>
            <x-button onclick="printTable()" class="mt-5">Download</x-button>
        </div>
    </div>

    <div id="transaction-table" class="relative overflow-x-auto border bg-white sm:rounded-lg">

        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Amount</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Sub Categories</th>
                    <th class="px-6 py-3">Budget</th>
                    <th class="px-6 py-3">Account</th>
                    <th class="px-6 py-3 text-right">Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr class="bg-white border-b  hover:bg-gray-50 ">
                        <td class="px-6 py-3">{{ $transaction?->created_at?->format('Y-m-d h:i A') }}</td>
                        <td class="px-6 py-3">
                            {{ Number::currency($transaction->amount ?? 0, auth()?->user()?->currency) }}
                        </td>
                        <td class="px-6 py-3">{{ $transaction->category->parent?->name ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $transaction->category?->name ?? '-' }}</td>

                        <td class="px-6 py-3">{{ $transaction->budget?->type ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $transaction->account?->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-right">

                            <span
                                class="capitalize {{ $transaction->type == 'income' ? 'bg-green-500' : 'bg-red-500' }} text-white px-3 py-1 rounded">
                                {{ $transaction->type }}

                            </span>
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-white border-b  hover:bg-gray-50 ">
                    <td colspan="4" class="px-6 py-3">
                        {{ $transactions->links() }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function printTable() {
        var table = document.getElementById('transaction-table').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = table;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
