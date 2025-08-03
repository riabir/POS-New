<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\User;

class EmployeeObserver
{
    /**
     * Handle the Employee "updated" event.
     */
    public function updated(Employee $employee): void
    {
        // Only update if status changed
        if ($employee->isDirty('status')) {
            // Find associated user by email
            $user = User::where('email', $employee->email)->first();
            
            if ($user) {
                // Update user status to match employee status
                $user->status = $employee->status;
                $user->save();
            }
        }
    }
}