<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;
    protected $fillable = ['amount', 'category_id', 'sub_category_id','spent', 'account_id','user_id','start_date','limit', 'end_date', 'exceeded'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account():BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

}
