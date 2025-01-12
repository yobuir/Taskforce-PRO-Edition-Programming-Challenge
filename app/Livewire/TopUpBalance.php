<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\Component;

class TopUpBalance extends Component
{
    public $account, $amount, $account_id;

    public $openModel = false;

    protected $rules = [
        'amount' => 'required|numeric|min:0',
        'account_id' => 'required|exists:accounts,id',
    ];

    public function toggleModel()
    {
        $this->openModel = $this->openModel ? false : true;
    }
    public function loadAccount()
    {
        $this->account = auth()
            ->user()
            ->accounts()
            ->where('id', $this->account_id)
            ->first();
    }


    public function store()
    {
        $this->validate();

        try {
            if ($this->account) {
                $this->account->balance += $this->amount;
                $this->account->save();
                Transaction::create(['account_id' => $this->account_id, 'type' => 'income', 'amount' => $this->amount, 'user_id' => auth()->user()->id, 'description' => 'income', 'transaction_date' => now()]);
                $this->resetInputs();
            }
            session()->flash('success', 'Balance updated ');
            $this->toggleModel();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }

        $this->redirectRoute('dashboard');
    }

    public function resetInputs(){
        $this->reset(['amount','account_id']);
    }

    public function render()
    {
        return view('livewire.top-up-balance', [
            'accounts' => auth()->user()->accounts()->get(),
        ]);
    }
}
