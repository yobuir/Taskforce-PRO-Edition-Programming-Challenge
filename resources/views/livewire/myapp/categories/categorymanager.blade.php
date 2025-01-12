<?php

use Livewire\Volt\Component;
use App\Models\Category;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    public $name, $parent_id, $category_id;

    protected $rules = [
        'name' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:categories,id',
    ];

    public $openModel = false;

    public function toggleModel()
    {
        $this->openModel = $this->openModel ? false : true;
    }

    public function store()
    {
        $this->validate();
        try {
            if ($this->category_id) {
                $category = Category::findOrFail($this->category_id);
            } else {
                $category = new Category();
            }

            $category->user_id = auth()->id();
            $category->name = $this->name;
            $category->parent_id = $this->parent_id;
            $category->save();

            $this->resetForm();
            session()->flash('success', 'Category added successfully!');
            $this->toggleModel();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }

        $this->redirectRoute('dashboard.categories', navigate: true);
    }

    public function edit(Category $category)
    {
        $this->category_id = $category->id;
        $this->name = $category->name;
        $this->parent_id = $category->parent_id;
        $this->toggleModel();
    }

    public function delete(Category $category)
    {
        try {
            if ($category->subcategories()->exists() || $category->transactions()->exists()) {
                session()->flash('error', 'Cannot delete category with subcategories or transactions.');
            } else {
                $category->delete();
                session()->flash('success', 'Category deleted successfully!');
            }
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }

        $this->redirectRoute('dashboard.categories', navigate: true);
    }

    public function resetForm()
    {
        $this->reset(['name', 'parent_id', 'category_id']);
    }

    public function with(): array
    {
        return [
            'categories' => auth()->user()->categories()->paginate(6),
            //  $this->categories = Category::with('subcategories')->paginate(10);
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
        <h1 class="text-2xl font-normal">My Categories</h1>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('dashboard') }}" class="underline text-blue-500">Dashboard</a>
        /
        <span>Categories</span>
    </div>
    <div class="flex flex-col gap-6">
        <div class="flex justify-end items-end">
            <x-button wire:click="toggleModel" wire:loading.attr="disabled">
                Add category
            </x-button>
        </div>
        <div class="relative overflow-x-auto border bg-white sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Parent</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr class="bg-white border-b  hover:bg-gray-50 ">
                            <td class="px-6 py-3">{{ $category->name }}</td>
                            <td class="px-6 py-3">{{ $category->parent?->name ?? '-' }}</td>
                            <td class="px-6 py-3 text-right">
                                <x-secondary-button wire:click="edit({{ $category->id }})"
                                    class="px-2 py-1">Edit</x-secondary-button>
                                <x-danger-button wire:click="delete({{ $category->id }})"
                                    class="px-2 py-1">Delete</x-danger-button>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-white border-b  hover:bg-gray-50 ">
                        <td colspan="4" class="px-6 py-3">
                            {{ $categories->links() }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>




        <x-dialog-modal wire:model.live="openModel">
            <x-slot name="title">
                {{ $category_id ? 'Edit Category' : 'Add new category' }}
            </x-slot>
            <x-slot name="content">
                <div class="grid grid-cols-1">
                    <div class="mb-4">
                        <label for="name" class="block font-medium">Category Name</label>
                        <input type="text" id="name" wire:model="name" class="w-full border rounded p-2">
                        <x-input-error for="name" />
                    </div>

                    <div class="mb-4">
                        <label for="parent_id" class="block font-medium">Parent Category</label>
                        <select id="parent_id" wire:model="parent_id" class="w-full border rounded p-2">
                            <option value="">None</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="parent_id" />
                    </div>
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
</div>
