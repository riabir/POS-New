<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Employee;
use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses for Admins/Accounts based on status.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending'); // Default to 'pending' tab
        
        $query = Expense::with(['employee', 'expenseType'])
                        ->where('status', $status)
                        ->latest();
        
        $expenses = $query->paginate(15)->withQueryString();

        // Data for the stat cards on the admin index page
        $unapprovedCount = Expense::whereIn('status', ['pending', 'verified'])->count();
        $unapprovedTotal = Expense::whereIn('status', ['pending', 'verified'])->sum('total');

        return view('expenses.index', compact('expenses', 'status', 'unapprovedCount', 'unapprovedTotal'));
    }

    /**
     * Display a simplified expense list for the currently logged-in user.
     */
    public function userIndex(Request $request)
    {
        $status = $request->query('status', 'pending');
        
        // Find the employee record associated with the logged-in user by email
        $employee = Employee::where('email', Auth::user()->email)->first();

        if (!$employee) {
            // Handle case where the logged-in user is not an employee in the system
            return redirect()->route('dashboard')->withErrors('You do not have an employee profile to view expenses.');
        }

        $query = Expense::with('expenseType')
                        ->where('employee_id', $employee->id) // CRITICAL: Only show their own expenses
                        ->where('status', $status)
                        ->latest();
        
        $expenses = $query->paginate(15)->withQueryString();

        return view('expenses.user_index', compact('expenses', 'status'));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        $employees = Employee::active()->orderBy('first_name')->get();
        $expenseTypes = ExpenseType::orderBy('name')->get();
        return view('expenses.create', compact('employees', 'expenseTypes'));
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'expense_type_id' => 'required|exists:expense_types,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'days' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'particulars' => 'nullable|string',
            'voucher' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('voucher')) {
            $validated['voucher'] = $request->file('voucher')->store('vouchers', 'public');
        }

        Expense::create($validated);
        
        $redirectRoute = $request->input('action') === 'create_and_another'
            ? 'expenses.create'
            : 'expenses.user.index';

        return redirect()->route($redirectRoute)->with('success', 'Expense submitted successfully.');
    }

    /**
     * Show the approval form for a specific expense.
     */
    public function showApproveForm(Expense $expense)
    {
        return view('expenses.approve', compact('expense'));
    }
    
    /**
     * Process the approval or rejection of an expense.
     */
    public function processApproval(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'approver_remarks' => 'nullable|string',
        ]);

        $expense->update([
            'status' => $validated['status'],
            'approver_remarks' => $validated['approver_remarks'],
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('expenses.index', ['status' => $validated['status']])->with('success', "Expense has been {$validated['status']}.");
    }

    /**
     * Show the payment form for an approved expense, using a clean layout.
     */
    public function showPayForm(Expense $expense)
    {
        if ($expense->status !== 'approved') {
            return redirect()->route('expenses.index')->withErrors('Only approved expenses can be paid.');
        }
        return view('expenses.pay', compact('expense'));
    }
    
    /**
     * Process the payment of an expense.
     */
    public function processPayment(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'payment_remarks' => 'nullable|string',
        ]);

        $expense->update([
            'status' => 'paid',
            'payment_remarks' => $validated['payment_remarks'],
            'paid_by' => Auth::id(),
            'paid_at' => now(),
        ]);

        return redirect()->route('expenses.paid')->with('success', 'Expense has been marked as paid.');
    }

    /**
     * Display a list of all paid expenses.
     */
    public function listPaid(Request $request)
    {
        $query = Expense::with(['employee', 'expenseType', 'approver', 'payer'])
                        ->where('status', 'paid')
                        ->latest('paid_at');
        
        $paidExpenses = $query->paginate(15)->withQueryString();

        return view('expenses.paid', compact('paidExpenses'));
    }

    /**
     * Download a PDF report of paid expenses within a date range.
     */
    public function downloadPaidReport(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);
        
        $dateFrom = Carbon::parse($request->date_from)->startOfDay();
        $dateTo = Carbon::parse($request->date_to)->endOfDay();
        
        $paidExpenses = Expense::with(['employee', 'expenseType', 'approver', 'payer'])
                        ->where('status', 'paid')
                        ->whereBetween('paid_at', [$dateFrom, $dateTo])
                        ->latest('paid_at')
                        ->get();

        $totals = [
            'total' => $paidExpenses->sum('total'),
        ];

        $pdf = Pdf::loadView('expenses.pdf_report', [
            'paidExpenses' => $paidExpenses,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'totals' => $totals,
        ]);
        
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('paid_expenses_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.pdf');
    }
}