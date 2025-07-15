<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role', // This is essential
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
     */
    public function isAccounts(): bool
    {
        return $this->hasRole('accounts');
    }
}