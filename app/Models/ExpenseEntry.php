<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseEntry extends Model
{
    protected $fillable = ['user_id', 'source', 'amount', 'tag', 'date', 'month', 'year'];
    protected $casts = ['amount' => 'decimal:2', 'date' => 'date'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
