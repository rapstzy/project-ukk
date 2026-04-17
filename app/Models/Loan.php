<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = ['user_id', 'loan_date', 'due_date', 'returned_at', 'status', 'fine_amount', 'is_extended'];
    protected $casts = ['loan_date' => 'date', 'due_date' => 'date', 'returned_at' => 'date', 'is_extended' => 'boolean'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(LoanItem::class);
    }

    public function ticket() {
        return $this->hasOne(Ticket::class);
    }
}
