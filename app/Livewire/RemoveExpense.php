<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Transaction;
use Livewire\Component;


class RemoveExpense extends Component
{



    public $account, $amount, $account_id, $category_id, $sub_categories, $budget_id, $budget, $usage_type;
    public $sub_category_id = [];

    public $openModel = false;

    protected $rules = [
        'usage_type'=>'required',
        'amount' => 'required|numeric|min:0',
        'account_id' => 'nullable|exists:accounts,id',
        'budget_id' => 'nullable|exists:budgets,id',
        'sub_category_id' => 'nullable|array',
        'sub_category_id.*' => 'exists:categories,id',
    ];

    public function toggleModel()
    {
        $this->openModel = $this->openModel ? false : true;
    }

    public function loadUsageType($value)
    {
        $this->usage_type = $value;
    }
    public function loadAccount()
    {
        $this->account = auth()
            ->user()
            ->accounts()
            ->where('id', $this->account_id)
            ->first();
    }


    public function loadBudget()
    {
        $this->budget = auth()
            ->user()
            ->budgets()
            ->where('id', $this->budget_id)
            ->first();
    }

    function loadBalance()
    {
        $this->budget = auth()
            ->user()
            ->budgets()
            ->where('id', $this->budget_id)
            ->first();
    }


    function loadSubCategory(){
        $this->sub_categories = Category::where('parent_id', $this->category_id)->get();
    }


    public function store()
    {
        $this->validate();
        $completed = 0;

        try {

            if ($this->usage_type == 'budget_type') {

                if ($this->budget) {
                    if ($this->budget->amount < $this->amount) {
                        session()->flash('error', 'Insufficient balance \n' . 'Available balance ' . $this->budget->amount);
                        return;
                    }
                    $this->budget->amount -= $this->amount;
                    $this->budget->spent += $this->amount;
                    $this->budget->save();
                    $completed = 1;
                }
            } elseif ($this->usage_type == 'account_type') {
                if ($this->account) {
                    if ($this->account->balance < $this->amount) {
                        session()->flash('error', 'Insufficient balance \n' . 'Available balance ' . $this->account->balance);
                        return;
                    }
                    $this->account->balance -= $this->amount;
                    $this->account->save();
                    $completed = 1;
                }
            }

            if($this->budget_id){
                $this->category_id=$this->budget->category_id;
            }
            if ($completed) {
                Transaction::create(['account_id' => $this->account_id, 'sub_category_id'=> json_encode($this->sub_category_id),'category_id'=>$this->category_id,'budget_id' => $this->budget_id, 'type' => 'expense', 'amount' => $this->amount, 'user_id' => auth()->user()->id, 'description' => 'expense', 'transaction_date' => now()]);
                session()->flash('success', 'Balance updated ');
                $this->toggleModel();
            } else {
                session()->flash('error', 'Process was not completed');
            }
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }

        $this->redirectRoute('dashboard');
    }

    public function resetInputs()
    {
        $this->reset(['amount', 'account_id']);
    }


    public function render()
    {
        return view(
            'livewire.remove-expense',
            [
                'accounts' => auth()->user()->accounts()->get(),
                'budgets' => auth()->user()->budgets()->get(),
                'categories' => auth()->user()->categories,
            ]
        );
    }
}
