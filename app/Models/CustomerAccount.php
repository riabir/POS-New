<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'customer_id',
        'transaction_type',
        'amount', // Original bill amount (should remain fixed)
        'paid_amount', // Actual payments received
        'due_date',
        'paid_date',
        'status',
    ];

    // Calculate the current balance based on ledger entries
    public function getBalanceAttribute()
    {
        // Start with the original bill amount
        $originalAmount = $this->amount;
        
        // Get all ledger entries for this sale
        $ledgerEntries = $this->sale->ledgers()
            ->where('account_type', 'liability')
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();
            
        // Calculate the running balance
        $balance = $originalAmount;
        
        foreach ($ledgerEntries as $entry) {
            // Subtract credits (payments, adjustments that reduce liability)
            if ($entry->credit > 0) {
                $balance -= $entry->credit;
            }
            // Add debits (adjustments that increase liability)
            if ($entry->debit > 0 && $entry->transaction_type !== 'sale') {
                $balance += $entry->debit;
            }
        }
        
        return max(0, $balance); // Ensure balance is not negative
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}