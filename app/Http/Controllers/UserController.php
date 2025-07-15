<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Show the form for creating a new user by an admin.
     */
    public function create()
    {
        // Authorization: Only allow users with the 'admin' role to see this page.
        if (!Auth::user()->isAdmin()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Authorization: Only allow users with the 'admin' role to perform this action.
        if (!Auth::user()->isAdmin()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in(['admin', 'user', 'accounts'])], // Validate the role
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Save the role
        ]);

        return redirect()->route('dashboard')->with('success', 'User created successfully!');
    }
}