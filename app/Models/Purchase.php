<?php

namespace App\Models;

// Make sure all these models are imported
use App\Models\PurchaseItem;
use App\Models\VendorAccount;
use App\Models\VendorLedger;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception; // Import the base Exception class

class Purchase extends Model
{
    protected $fillable = ['vendor_id', 'purchase_no', 'purchase_date', 'remarks', 'sub_total', 'discount', 'grand_total', 'status'];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    /**
     * The "booted" method of the model.
     * This is where we register our robust model event listeners.
     */
    protected static function booted(): void
    {
        static::deleting(function (Purchase $purchase) {
            
            DB::transaction(function () use ($purchase) {

                // --- SMART STOCK & SERIAL NUMBER REVERSAL ---
                // We must iterate through the purchase items *before* they are deleted.
                foreach ($purchase->items as $item) {
                    $stock = Stock::where('product_id', $item->product_id)->first();

                    // --- FIX #1: PREVENT DELETION IF STOCK IS ALREADY USED ---
                    // If stock doesn't exist or if current quantity is less than what this purchase added,
                    // we cannot safely delete. Stop the entire process.
                    if (!$stock || $stock->quantity < $item->quantity) {
                        // Throw an exception with a clear message. The controller will catch this.
                        throw new Exception("Cannot delete purchase. Stock for product '{$item->product->product_name}' has already been sold or transferred.");
                    }

                    // --- FIX #2: CORRECTLY REMOVE SERIAL NUMBERS ---
                    // Check if there are serial numbers to process on both the stock and the item.
                    if (!empty($stock->serial_numbers) && !empty($item->serial_numbers)) {
                        // Get the array of serials from the master stock record.
                        $currentStockSerials = $stock->serial_numbers;
                        
                        // Get the array of serials from the purchase item we are deleting.
                        $serialsToRemove = $item->serial_numbers;

                        // Calculate the new array of serials by removing the ones from this purchase.
                        $newStockSerials = array_diff($currentStockSerials, $serialsToRemove);
                        
                        // Re-index the array to ensure it's a clean JSON array (e.g., [0=>'A', 1=>'B'])
                        // instead of an object with non-sequential keys.
                        $stock->serial_numbers = array_values($newStockSerials);
                    }
                    
                    // Now, subtract the quantity.
                    $stock->quantity -= $item->quantity;
                    $stock->save();
                }

                // --- CLEANUP OF OTHER RELATED DATA (this part was already correct) ---
                $purchase->items()->delete();
                VendorAccount::where('purchase_id', $purchase->id)->delete();
                VendorLedger::where('purchase_id', $purchase->id)->delete();
                
            });
        });
    }

    // ... your relationships (vendor(), items()) remain the same ...
    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function items() { return $this->hasMany(PurchaseItem::class); }
}