<?php

use Livewire\Volt\Component;
use App\Models\Budget;
use App\Models\Account;
use App\Models\Category;
new class extends Component {
    public $openModel = false;

    public function toggleModel()
    {
        $this->openModel = $this->openModel ? false : true;
    }

    public $amount, $account_id,$limit ,$category_id, $sub_categories, $account, $budget_id, $start_date, $end_date;
    public $sub_category_id = [];

    protected $rules = [
        'amount' => 'required|numeric|min:0',
        'limit' => 'required|numeric|min:0|lte:amount',
        'account_id' => 'nullable|exists:accounts,id',
        'category_id' => 'nullable|exists:categories,id',
        'start_date' => 'required|date|after:today|before:end_date',
        'end_date' => 'required|date|after:start_date',
        'sub_category_id' => 'nullable|array',
        'sub_category_id.*' => 'exists:categories,id',
    ];

    public function store()
    {
        try {
            $this->validate();
            if ($this->budget_id) {
                $Budget = Budget::findOrFail($this->budget_id);
            } else {
                $Budget = new Budget();
            }

            if ($this->account) {
                if ($this->account->balance < $this->amount) {
                    session()->flash('error', 'Insufficient balance \n' . 'Available balance ' . $this->account->balance);
                    return;
                }

                $Budget->user_id = auth()->id();
                $Budget->amount = $this->amount;
                $Budget->account_id = $this->account_id;
                $Budget->category_id = $this->category_id;
                $Budget->sub_category_id = json_encode($this->sub_category_id);
                $Budget->start_date = $this->start_date;
                $Budget->end_date = $this->end_date;
                $Budget->limit=$this->limit;
                $Budget->save();

                $this->account->balance -= $this->amount;
                $this->account->save();
                $this->resetInputs();
            }

            session()->flash('success', 'Account added successfully!');
            $this->toggleModel();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }

        $this->redirectRoute('dashboard.budgets', navigate: true);
    }

    public function resetInputs()
    {
        $this->amount = null;
        $this->account_id = null;
        $this->category_id = null;
        $this->start_date = null;
        $this->end_date = null;
    }

    public function loadAccount()
    {
        $this->account = auth()
            ->user()
            ->accounts()
            ->where('id', $this->account_id)
            ->first();
    }

    function loadSubCategory()
    {
        $this->sub_categories = Category::where('parent_id', $this->category_id)->get();
    }

    public function delete($accountId)
    {
        try {
            $account = Budget::findOrFail($accountId);
            $account->delete();

            session()->flash('success', 'budget deleted successfully!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }

        $this->redirectRoute('dashboard.budgets', navigate: true);
    }

    public function edit($budget_id)
    {
        $budget = Budget::findOrFail($budget_id);
        if ($this->category_id) {
            $this->loadSubCategory();
        }

        $this->budget_id = $budget->id;
        $this->amount = $budget->amount;
        $this->account_id = $budget->account_id;
        $this->category_id = $budget->category_id;
        $this->sub_category_id = json_decode($budget->sub_category_id, true);
        $this->start_date = $budget->start_date;
        $this->end_date = $budget->end_date;
        $this->toggleModel();
    }

    public function with(): array
    {
        return [
            'accounts' => auth()->user()->accounts()->get(),
            'categories' => auth()->user()->categories,
            'budgets' => Budget::with(['account', 'category'])
                ->where('user_id', auth()->id())
                ->get(),
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

    <div class="">
        <h1 class="text-2xl font-normal">My Budgets</h1>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('dashboard') }}"  class="underline text-blue-500">Dashboard</a>
        /
        <span>Budgets</span>
    </div>
    <div class="grid lg:grid-cols-3 grid-cols-1 gap-3">

        @forelse ($budgets as $budget)
            <div
                class="relative {{ ($budget->exceeded  or $budget->spent > $budget->amount) ? 'border-red-500' : 'border-gray-200 ' }} group block max-w-sm py-8 px-10 bg-white border rounded-lg shadow hover:bg-gray-50 ">
                <a href="{{ route('dashboard.accounts') }}" wire:navigate class="">
                    <h5 class="tracking-tight text-sm font-light text-gray-900 flex gap-3 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        <p class="flex flex-col">
                            <span class="text-xs text-blue-500">(Start date )</span>
                            {{ $budget?->start_date?->format('Y, M-d') }}
                        </p>
                        <span class="font-extrabold">-</span>
                        <p class="flex flex-col">
                            <span class="text-xs text-red-500">(End date )</span>
                            {{ $budget?->end_date?->format('Y, M-d') }}
                        </p>
                    </h5>
                    <p class="text-sm text-gray-500 mt-3  font-medium">
                        {{ $budget?->category?->name }} |
                        @if ($budget->exceeded or $budget->spent > $budget->amount)
                            <span class="text-red-600 bg-red-500/10 p-1">
                                Usage exceeded - {{ Number::currency($budget->spent ?? 0, auth()?->user()?->currency) }}
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded">
                                Budget: {{ Number::currency($budget?->amount ?? 0, auth()?->user()?->currency) }}
                                <span class="text-red-500" title="Amount spent"> -
                                    {{ Number::currency($budget?->spent ?? 0, auth()?->user()?->currency) }}</span>
                            </span>
                        @endif
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        @if ($budget->sub_category_id)
                            @php
                                $subCategories = \App\Models\Category::whereIn(
                                    'id',
                                    json_decode($budget->sub_category_id, true),
                                )->get();
                            @endphp
                            @foreach ($subCategories as $subCategory)
                                @if ($subCategory)
                                    <span class="bg-gray-200 text-gray-800 text-xs mr-1 px-2.5 py-0.5 rounded">
                                        {{ $subCategory->name }}
                                    </span>
                                @endif
                            @endforeach
                        @endif

                    </p>
                </a>
                <div
                    class="absolute w-full h-full bg-white justify-center items-center top-0 right-0 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <x-secondary-button wire:click="edit({{ $budget->id }})"
                        class="btn btn-warning btn-sm">Edit</x-secondary-button>
                    <x-danger-button wire:confirm="Are you sure you want to delete this account?"
                        wire:click="delete({{ $budget->id }})" class="btn btn-danger btn-sm">Delete</x-danger-button>
                </div>
            </div>
        @empty
            <p>No budgets found</p>
        @endforelse
    </div>
    <div class="flex">
        <x-button wire:click="toggleModel" wire:loading.attr="disabled" class="gap-3 p-6 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>Add new budget</span>
        </x-button>
    </div>


    <x-dialog-modal wire:model.live="openModel">
        <x-slot name="title">
            {{ $budget_id ? 'Edit budget' : 'Add new budget' }}
        </x-slot>
        <x-slot name="content">
            <div class="mb-4">
                <label for="amount" class="block font-medium">Budget Amount</label>
                <input type="number" min="0" id="amount" wire:model="amount"
                    class="w-full border rounded p-2">
                <x-input-error for="amount" />
            </div>
             <div class="mb-4">
                <label for="limit" class="block font-medium">Budget minimum limit</label>
                <input type="number" min="0" id="limit" wire:model="limit"
                    class="w-full border rounded p-2">
                <x-input-error for="limit" />
            </div>

            <div class="mb-4">
                <x-label for="account_id" class="block font-medium">Account</x-label>
                <select id="account_id" wire:change="loadAccount" wire:model="account_id"
                    class="w-full border rounded p-2">
                    <option value="">None</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>
                <x-input-error for="account_id" />
            </div>

            <div class="mb-4">
                <x-label for="category_id" class="block font-medium">Category</x-label>
                <select id="category_id" wire:change="loadSubCategory" wire:model="category_id"
                    class="w-full border rounded p-2">
                    <option value="">None</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <x-input-error for="category_id" />
            </div>
            @if ($category_id and $sub_categories)
                <div class="mb-4">
                    <x-label for="sub_category_id" class="block font-medium">Choose SubCategories</x-label>
                    <select id="sub_category_id" multiple wire:model="sub_category_id"
                        class="w-full border rounded p-2">
                        <option value="">None</option>
                        @foreach ($sub_categories as $sub_category)
                            <option value="{{ $sub_category->id }}">{{ $sub_category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="sub_category_id" />
                </div>
            @endif
            <div>
                {{ $this?->account?->amount }}
            </div>
            <div class="mb-4">
                <label for="start_date" class="block font-medium">Start Date</label>
                <x-input type="date" id="start_date" wire:model="start_date" class="w-full border rounded p-2" />

                <x-input-error for="start_date" />
            </div>
            <div class="mb-4">
                <x-label for="end_date" class="block font-medium">End Date</x-label>
                <x-input type="date" id="end_date" wire:model="end_date" class="w-full border rounded p-2" />
                <x-input-error for="end_date" />
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('openModel')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="store" wire:loading.attr="disabled">
                <div wire:loading.delay wire:target="store">Saving...</div>
                <span wire:loading.remove wire:target="store">{{ __('Save & continue') }}</span>
            </x-button>
        </x-slot>
    </x-dialog-modal>

</div>
