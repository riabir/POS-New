<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Employee; // <-- 1. IMPORT THE EMPLOYEE MODEL

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    protected $hidden = [ 'password', 'remember_token' ];

    protected $casts = [ 'email_verified_at' => 'datetime', 'password' => 'hashed' ];

    /**
     * Checks if the user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Helper to check for the 'admin' role.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Helper to check for the 'accounts' role.
     * Note: I changed this to 'accounts' to match your model.
     * If your role name in the database is 'accountant', change it here.
     */
    public function isAccounts(): bool
    {
        return $this->hasRole('accounts');
    }

    // ==========================================================
    //  2. ADD THIS RELATIONSHIP METHOD
    // ==========================================================
    /**
     * Get the employee record associated with the user.
     *
     * This defines a one-to-one relationship based on the email address.
     * It assumes that the `email` in the `users` table matches the `email`
     * in the `employees` table.
     */
    public function employee()
    {
        return $this->hasOne(Employee::class, 'email', 'email');
    }
}