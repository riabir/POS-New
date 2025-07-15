<?php

namespace App\Models;

// 1. Import all the necessary models and facades
use App\Models\SaleItem;
use App\Models\Stock;
use App\Models\CustomerAccount;
use App\Models\CustomerLedger;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Sale extends Model
{
    // Make sure 'bill_date' is in your $fillable array
    protected $fillable = ['customer_id', 'bill_no', 'bill_date', 'remarks', 'sub_total', 'discount', 'grand_total', 'status'];

    // And in your casts array
    protected $casts = [
        'bill_date' => 'date',
    ];

    /**
     * The "booted" method of the model.
     * This is where we register our model event listeners for safe deletion.
     */
    protected static function booted(): void
    {
        // Register a 'deleting' event listener.
        // This closure will execute right before a Sale record is deleted.
        static::deleting(function (Sale $sale) {
            
            // Wrap everything in a database transaction for data integrity.
            DB::transaction(function () use ($sale) {

                // REQUIREMENT: RETURN ITEMS AND SERIALS TO STOCK
                // We must iterate through the items of the sale *before* they are deleted.
                foreach ($sale->items as $item) {
                    // Find the master stock record for the product in this sale item.
                    $stock = Stock::where('product_id', $item->product_id)->first();

                    // If a stock record for this product exists, add the quantity and serials back.
                    if ($stock) {
                        // 1. Add the quantity from this sale back to the main stock quantity.
                        $stock->quantity += $item->quantity;

                        // 2. Add the serial numbers from this sale back to the main stock serials array.
                        // We check if either array has serials to avoid errors.
                        if (!empty($stock->serial_numbers) || !empty($item->serial_numbers)) {
                            $currentStockSerials = $stock->serial_numbers ?? [];
                            $serialsToReturn = $item->serial_numbers ?? [];
                            
                            // Merge the arrays, ensure every serial is unique, and re-index the array.
                            $stock->serial_numbers = array_values(array_unique(array_merge($currentStockSerials, $serialsToReturn)));
                        }
                        
                        $stock->save();
                    }
                }

                // --- CLEANUP OF OTHER RELATED DATA ---
                
                // Delete the SaleItem records.
                $sale->items()->delete();

                // Delete the CustomerAccount invoice.
                CustomerAccount::where('sale_id', $sale->id)->delete();

                // Delete all CustomerLedger entries for this sale (invoice and payments).
                CustomerLedger::where('sale_id', $sale->id)->delete();
                
            });
        });
    }


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
}
