<div class="flex lg:flex-row flex-col justify-between gap-3 w-full">
    <div class="flex gap-3">
        @livewire('currency-change')
    </div>
    <div class="flex gap-4 flex-wrap">
        <a href="{{ route('dashboard') }}"
            class="{{ request()->routeIs('dashboard') ? ' underline font-extrabold' : '' }}" >Home</a>
        <a href="{{ route('dashboard.accounts') }}"
            class="{{ request()->routeIs('dashboard.accounts') ? ' underline font-extrabold' : '' }}"
            wire:navigate>Accounts</a>
        <a href="{{ route('dashboard.budgets') }}"
            class="{{ request()->routeIs('dashboard.budgets') ? ' underline font-extrabold' : '' }}"
            wire:navigate>Budgets</a>
        <a href="{{ route('dashboard.transactions') }}"
            class="{{ request()->routeIs('dashboard.transactions') ? ' underline font-extrabold' : '' }}"
            wire:navigate>Transactions</a>
    </div>

</div>
