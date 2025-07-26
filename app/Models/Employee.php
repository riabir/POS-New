<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // NEW: Import the Builder class for scopes

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'emp_id',
        'status',
        'reporting_manager',
        'branches',
        'designation',
        'department',
        'date_of_join',
        'date_of_probation',
        'personal_phone_number',
        'office_phone_number',
        'emergency_contact',
        'fathers_name',
        'mothers_name',
        'date_of_birth',
        'marital_status',
        'blood_group',
        'religion',
        'present_address',
        'permanent_address',
        'nid_number',
        'passport_number',
        'tin_number',
        'bank_name',
        'bank_branch',
        'bank_account_number',
        'photo',
        'nid_attachment',
        'passport_attachment',
        'tin_attachment',
        'cv_pdf',
        'certificate_attachment',
        'reference_name',
        'reference_phone_number',
        'education_details',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'education_details' => 'array',
        'date_of_birth' => 'date',
        'date_of_join' => 'date',
        'date_of_probation' => 'date',
        'status' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($employee) {
            if (empty($employee->emp_id)) {
                $latest = self::orderBy('id', 'desc')->first();
                $nextNumber = $latest ? (int) $latest->emp_id + 1 : 880242;
                $employee->emp_id = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the employee's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    // NEW: Add an accessor for a primary mailing address
    public function getMailingAddressAttribute()
    {
        return $this->present_address ?? $this->permanent_address ?? 'No address on file';
    }

    // --- RELATIONSHIPS ---

    public function salaryStructures()
    {
        return $this->hasMany(SalaryStructure::class)->orderBy('effective_date', 'desc');
    }

    public function currentSalaryStructure()
    {
        return $this->salaryStructures()->latest('effective_date')->first();
    }
    
    public function payouts()
    {
        return $this->hasMany(Payout::class)->orderBy('payout_date', 'desc');
    }

    // --- SCOPES ---

    /**
     * NEW: Scope a query to only include active employees.
     * Usage: Employee::active()->get();
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', true);
    }

    /**
     * NEW: Scope a query to only include inactive employees.
     * Usage: Employee::inactive()->get();
     */
    public function scopeInactive(Builder $query): void
    {
        $query->where('status', false);
    }
}