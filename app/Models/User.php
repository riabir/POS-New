<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Employee;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status', 
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean', // Add this
    ];

    // Existing role methods
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
    
    public function isAccounts(): bool
    {
        return $this->hasRole('accounts');
    }

    // Employee relationship
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'email', 'email');
    }

    // Updated isActive method to check user's own status
    public function isActive(): bool
    {
        return $this->status;
    }
    
    // Method to deactivate user
    public function deactivate(): void
    {
        $this->status = false;
        $this->save();
    }
    
    // Method to activate user
    public function activate(): void
    {
        $this->status = true;
        $this->save();
    }
    
    // Model event to automatically update status when employee status changes
    protected static function booted()
    {
        static::updated(function ($user) {
            // If employee relationship exists and status changed
            if ($user->employee && $user->isDirty('employee_id')) {
                // Sync status with employee
                $user->status = $user->employee->status;
                $user->saveQuietly(); // Save without triggering events again
            }
        });
        
        // When user is created, set status based on employee
        static::created(function ($user) {
            if ($user->employee) {
                $user->status = $user->employee->status;
                $user->saveQuietly();
            }
        });
    }
}