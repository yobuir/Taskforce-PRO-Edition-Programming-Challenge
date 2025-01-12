<div>
        <div class="flex">
        <x-button wire:click="toggleModel" wire:loading.attr="disabled" class="gap-3 p-6 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>Balance</span>
        </x-button>
    </div>


    <x-dialog-modal wire:model.live="openModel">
        <x-slot name="title">
            Top up balance
        </x-slot>
        <x-slot name="content">
            <div class="mb-4">
                <label for="amount" class="block font-medium">Amount</label>
                <input type="number" min="0" id="amount" wire:model="amount"
                    class="w-full border rounded p-2">
                <x-input-error for="amount" />
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
