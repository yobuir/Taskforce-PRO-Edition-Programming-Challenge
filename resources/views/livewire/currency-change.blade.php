<div class="flex gap-3">
    <button wire:click="ChangeCurrency('RWF')" class="{{ auth()->user()->currency == 'RWF' ? 'font-extrabold text-blue-500 underline' : '' }}">RWF</button>
    <button wire:click="ChangeCurrency('USD')" class="{{ auth()->user()->currency == 'USD' ? 'font-extrabold text-blue-500 underline' : '' }}">USD</button>
</div>
