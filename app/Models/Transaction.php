<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    /**
     * Get the category that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    function scopeExpenses($query){
        return $query->whereHas("category", function ($query) {
            $query->where("is_expense", true);
        });
    }

    function scopeIncomes($query){
        return $query->whereHas("category", function ($query) {
            $query->where("is_expense", false);
        });
    }

}
