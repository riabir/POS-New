<?php

namespace App\Models;

// Import all necessary models and facades
use App\Models\SaleItem;
use App\Models\Stock;
use App\Models\CustomerAccount;
use App\Models\CustomerLedger;
use App\Models\SaleCommission; // Make sure this is imported
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'customer_id', 
        'bill_no', 
        'bill_date', 
        'remarks', 
        'sub_total', 
        'discount', 
        'grand_total', 
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'bill_date' => 'date',
    ];

    /**
     * The "booted" method of the model.
     * Registers model event listeners for safe deletion.
     */
    protected static function booted(): void
    {
        static::deleting(function (Sale $sale) {
            // Wrap everything in a database transaction for data integrity.
            DB::transaction(function () use ($sale) {
                // Return items and serials to stock
                foreach ($sale->items as $item) {
                    $stock = Stock::where('product_id', $item->product_id)->first();
                    if ($stock) {
                        $stock->quantity += $item->quantity;
                        if (!empty($stock->serial_numbers) || !empty($item->serial_numbers)) {
                            $currentStockSerials = $stock->serial_numbers ?? [];
                            $serialsToReturn = $item->serial_numbers ?? [];
                            $stock->serial_numbers = array_values(array_unique(array_merge($currentStockSerials, $serialsToReturn)));
                        }
                        $stock->save();
                    }
                }

                // Delete related records
                $sale->commissions()->delete();
                $sale->items()->delete();
                CustomerAccount::where('sale_id', $sale->id)->delete();
                CustomerLedger::where('sale_id', $sale->id)->delete();
            });
        });
    }

    // ===============================================
    //               RELATIONSHIPS
    // ===============================================

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function ledgers()
    {
        return $this->hasMany(CustomerLedger::class);
    }

    /**
     * Get the commissions associated with the sale.
     */
    public function commissions()
    {
        return $this->hasMany(SaleCommission::class);
    }

    // ===============================================
    //               ACCESSORS & LOGIC
    // ===============================================

    /**
     * MODIFIED: Calculate the true NET profit for the sale.
     * This now includes the deduction for any sales commissions paid.
     *
     * @return float
     */
    public function getTotalProfitAttribute(): float
    {
        // 1. Calculate the gross profit from the items sold
        // Formula: SUM of (quantity * (sale_price - cost_price)) for each item
        $grossProfit = $this->items->sum(function ($item) {
            return $item->quantity * ($item->unit_price - $item->cost_price);
        });

        // 2. Calculate the total cost of commissions for this sale
        $totalCommissionCost = $this->commissions->sum('amount');

        // 3. Return the net profit
        return $grossProfit - $totalCommissionCost;
    }
}