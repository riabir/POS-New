<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }
    /**
     * Show the form for creating a new user from an employee.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }
        // --- DEBUGGING ---
        // Test 1: See all active employees. If this list is empty, your 'status' column is the problem.
        // dd(Employee::active()->get());
        // Test 2: See all emails that are already in the users table.
        // dd(User::pluck('email'));

        // Get active employees who do not already have a user account
        $employees = Employee::active()
            ->whereNotIn('email', User::pluck('email'))
            ->orderBy('first_name')
            ->get();
        // Test 3: See the FINAL list of employees being sent to the view.
        // If this is empty, it means all your active employees are already users.
        // dd($employees);
        return view('users.create', compact('employees'));
    }
    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in(['admin', 'user', 'accounts'])],
        ]);
        
        $employee = Employee::findOrFail($request->employee_id);
        User::create([
            'name' => $employee->full_name,
            'phone' => $employee->personal_phone_number,
            'email' => $employee->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => true, // Set status to active by default
        ]);
        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }
    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        if (!$authUser->isAdmin()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }
        return view('users.edit', compact('user'));
    }
    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        if (!$authUser->isAdmin()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }
        $request->validate([
            'role' => ['required', 'string', Rule::in(['admin', 'user', 'accounts'])],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);
        $data['role'] = $request->role;
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }
    
    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        if (!$authUser->isAdmin()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }
        
        // Prevent self-deletion
        if ($authUser->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}