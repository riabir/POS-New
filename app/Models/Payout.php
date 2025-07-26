<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payout extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'amount',
        'payout_type',
        'payout_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payout_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the employee that this payout belongs to.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the list of allowed ENUM values for the payout_type column.
     * This robust method prevents parsing errors and makes the form dropdown
     * dynamic based on the database schema.
     *
     * @return array
     */
    public static function getPayoutTypes(): array
    {
        // Get the column type definition from the database.
        $result = DB::select("SHOW COLUMNS FROM `payouts` WHERE Field = 'payout_type'");
        
        // Safety check: if column doesn't exist or isn't an ENUM, return an empty array.
        if (empty($result) || !isset($result[0]->Type)) {
            return [];
        }
        
        // Use a regular expression to extract the comma-separated values from the ENUM definition.
        preg_match('/^enum\((.*)\)$/', $result[0]->Type, $matches);
        
        // Safety check: if regex fails to find a match, return an empty array.
        if (!isset($matches[1])) {
            return [];
        }
        
        // 1. Get the raw string, e.g., "'Value 1','Value 2','Value 3'"
        $enum_string = $matches[1];
        
        // 2. Remove all single quotes to get a clean string, e.g., "Value 1,Value 2,Value 3"
        $values_without_quotes = str_replace("'", "", $enum_string);
        
        // 3. Explode the clean string by the comma into a final, correct array.
        $payout_types = explode(',', $values_without_quotes);
        
        return $payout_types;
    }
}