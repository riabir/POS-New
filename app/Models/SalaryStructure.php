<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'house_rent_allowance',
        'medical_allowance',
        'conveyance_allowance',
        'effective_date',
        'notes',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'basic_salary' => 'decimal:2',
        'house_rent_allowance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'conveyance_allowance' => 'decimal:2',
    ];

    /**
     * Get the employee that this salary structure belongs to.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Accessor to calculate the total gross salary on the fly.
     * You can access this like a property: $salaryStructure->total_gross_salary
     */
    protected function totalGrossSalary(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->basic_salary
            + $this->house_rent_allowance
            + $this->medical_allowance
            + $this->conveyance_allowance,
        );
    }
}