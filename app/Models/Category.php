<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'user_id','parent_id'];

    public function subcategories():HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent():BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function transactions():HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
