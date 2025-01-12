<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{

    use SoftDeletes;
    protected $fillable = ['account_id','budget_id', 'category_id', 'sub_category_id','type', 'amount','user_id','description', 'transaction_date'];

    public function account():BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
