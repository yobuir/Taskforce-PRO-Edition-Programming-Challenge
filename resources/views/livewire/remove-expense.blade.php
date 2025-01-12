<div>
    <div class="flex">
        <x-danger-button wire:click="toggleModel" wire:loading.attr="disabled" class="gap-3 p-6 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>

            <span>Expense</span>
        </x-danger-button>
    </div>


    <x-dialog-modal wire:model.live="openModel">
        <x-slot name="title">
            Add Expense
        </x-slot>
        <x-slot name="content">

            <div class="flex mb-4">
                <div class="flex items-center me-4">
                    <input id="inline-radio" type="radio" wire:click="loadUsageType('budget_type')"
                        value="budget_type" wire:model.live="usage_type" name="usage_type"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2 ">
                    <label for="inline-radio" class="ms-2 text-sm font-medium text-gray-900 ">Use Budget
                    </label>
                </div>
                <div class="flex items-center me-4">
                    <input id="inline-2-radio" type="radio" wire:click="loadUsageType('account_type')"
                        value="account_type" wire:model.live="usage_type" name="usage_type"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2 ">
                    <label for="inline-2-radio" class="ms-2 text-sm font-medium text-gray-900 ">Use Account </label>
                </div>
            </div>
            <x-input-error for="usage_type" />

            @if ($this->usage_type == 'account_type')
                <div class="mb-4">
                    <x-label for="account_id" class="block font-medium">Choose Account</x-label>
                    <select id="account_id" wire:change="loadAccount" wire:model="account_id"
                        class="w-full border rounded p-2">
                        <option value="">None</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="account_id" />
                </div>
                Available Balance: {{ Number::currency($this?->account?->balance ?? 0, auth()?->user()?->currency) }}

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
                        <x-input-error for="sub_category" />
                    </div>
                @endif
            @elseif($this->usage_type == 'budget_type')
                <div class="mb-4">
                    <x-label for="budget_id" class="block font-medium">Choose Budget</x-label>
                    <select id="budget_id" wire:change="loadBudget" wire:model="budget_id"
                        class="w-full border rounded p-2">
                        <option value="">None</option>
                        @foreach ($budgets as $budget)
                            <option value="{{ $budget->id }}">{{ $budget?->category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="budget_id" />
                </div>
                Available Balance:
                {{ Number::currency($this?->budget?->amount ?? 0, auth()?->user()?->currency) }}
            @endif
            <div class="mb-4">
                <label for="amount" class="block font-medium">Amount</label>
                <input type="number" min="0" id="amount" wire:model="amount"
                    class="w-full border rounded p-2">
                <x-input-error for="amount" />
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
