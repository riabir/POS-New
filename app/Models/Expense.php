<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'expense_type_id',
        'from_date',
        'to_date',
        'days',
        'amount',
        'total',
        'particulars',
        'voucher',
        'status',
        'verified_by',
        'verified_at',
        'verifier_remarks',
        'approved_by',
        'approved_at',
        'approver_remarks',
        'paid_by',
        'paid_at',
        'payment_remarks',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relationships
    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function expenseType() {
        return $this->belongsTo(ExpenseType::class);
    }

    public function verifier() {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver() {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payer() {
        return $this->belongsTo(User::class, 'paid_by');
    }
}