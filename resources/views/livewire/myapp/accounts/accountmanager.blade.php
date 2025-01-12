<?php

use Livewire\Volt\Component;
use App\Models\Account;
new class extends Component {
    public $openModel = false;

    public function toggleModel()
    {
        $this->openModel = $this->openModel ? false : true;
    }

    public $accountId, $name, $type, $balance;

    protected $rules = [
        'name' => 'required|string|max:255',
        'balance' => 'required|numeric|min:0',
        'type' => 'required|in:bank,mobile_money,cash,other',
    ];

    public function store()
    {
        $this->validate();

        try {
            if ($this->accountId) {
                $account = Account::findOrFail($this->accountId);
            } else {
                $account = new Account();
            }
            $account->user_id = auth()->id();
            $account->name = $this->name;
            $account->balance = $this->balance;
            $account->type = $this->type;
            $account->save();

            $this->toggleModel();
            $this->reset(['name', 'type', 'balance']);

            session()->flash('success', $this->accountId ? 'Account updated successfully!' : 'Account added successfully!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }

        $this->redirectRoute('dashboard.accounts', navigate: true);
    }

    public function delete($accountId)
    {
        try {
            $account = Account::findOrFail($accountId);
            $account->delete();

            session()->flash('success', 'Account deleted successfully!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }

        $this->redirectRoute('dashboard.accounts', navigate: true);
    }

    public function edit($accountId)
    {
        $account = Account::findOrFail($accountId);

        $this->accountId = $account->id;
        $this->name = $account->name;
        $this->type = $account->type;
        $this->balance = $account->balance;

        $this->toggleModel();
    }

    public function with(): array
    {
        return [
            'accounts' => auth()->user()->accounts()->get(),
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
        <h1 class="text-2xl font-normal">My Accounts</h1>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('dashboard') }}" class="underline text-blue-500">Dashboard</a>
        /
        <span>Accounts</span>
    </div>
    <div class="flex flex-wrap gap-3">

        @forelse ($accounts as $account)
            <div class="relative group ">
                <a href="{{ route('dashboard.accounts') }}" wire:navigate
                    class="block max-w-sm py-8 px-10 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                    <h5 class=" text-2xl tracking-tight text-gray-900 flex gap-3 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                        </svg>
                        {{ $account?->name }}
                    </h5>
                    <span class="text-sm text-gray-500">
                        {{ $account?->type }} |
                        {{ Number::currency($account?->balance ?? 0, auth()?->user()?->currency) }}
                    </span>
                </a>
                <div
                    class="absolute w-full h-full bg-white justify-center items-center top-0 right-0 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <x-secondary-button wire:click="edit({{ $account->id }})"
                        class="btn btn-warning btn-sm">Edit</x-secondary-button>
                    <x-danger-button wire:confirm="Are you sure you want to delete this account?"
                        wire:click="delete({{ $account->id }})" class="btn btn-danger btn-sm">Delete</x-danger-button>
                </div>
            </div>
        @empty
            <p>No accounts found</p>
        @endforelse
    </div>
    <div class="flex">
        <x-button wire:click="toggleModel" wire:loading.attr="disabled" class="gap-3 p-6 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>Add new account</span>
        </x-button>
    </div>
    <x-dialog-modal wire:model.live="openModel">
        <x-slot name="title">
            {{ $accountId ? 'Edit Account' : 'Add new account' }}
        </x-slot>
        <x-slot name="content">
            <div class="form-group mb-3">
                <x-label for="name">Account Name</x-label>
                <x-input type="text" wire:model="name" class="w-full" id="name" placeholder="Enter account name"
                    required />
                <x-input-error for="name" />
            </div>

            <div class="form-group mb-3">
                <x-label for="type">Account Type</x-label>
                <select wire:model="type" class="w-full" id="type" required>
                    <option value="">Choose type</option>
                    <option value="bank">Bank</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="cash">Cash</option>
                    <option value="other">Other</option>
                </select>
                <x-input-error for="type" />
            </div>

            @if (!$this->accountId)
                <div class="form-group mb-3">
                    <x-label for="balance">Starting Balance</x-label>
                    <x-input type="number" wire:model="balance" class="w-full" id="balance"
                        placeholder="Enter initial balance" required />
                    <x-input-error for="balance" />
                </div>
            @endif

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
