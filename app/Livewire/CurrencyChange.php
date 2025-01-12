<?php

namespace App\Livewire;

use Livewire\Component;

class CurrencyChange extends Component
{
    public function ChangeCurrency($value)
    {
        try {
            $user = auth()->user();
            $user->currency = $value;
            $user->save();
            session()->flash('success', 'Currency have been changed');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }

        $this->redirectRoute('dashboard');
    }
    public function render()
    {
        return view('livewire.currency-change');
    }
}
