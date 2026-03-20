<?php

namespace App\Http\Controllers\Loans;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with(['customer', 'branch', 'loanOfficer']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($subQ) use ($search) {
                    $subQ->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('loan_type', 'like', "%{$search}%")
                ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('loan_type')) {
            $query->where('loan_type', $request->loan_type);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('application_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('application_date', '<=', $request->date_to);
        }

        $loans = $query->latest()->paginate(20);

        $stats = [
            'total_loans' => Loan::count(),
            'pending_loans' => Loan::where('status', 'pending')->count(),
            'approved_loans' => Loan::where('status', 'approved')->count(),
            'disbursed_loans' => Loan::where('status', 'disbursed')->count(),
            'active_loans' => Loan::where('status', 'active')->count(),
            'completed_loans' => Loan::where('status', 'completed')->count(),
            'defaulted_loans' => Loan::where('status', 'defaulted')->count(),
            'total_portfolio' => Loan::sum('amount'),
            'outstanding_balance' => Loan::sum('outstanding_balance'),
            'new_this_month' => Loan::whereMonth('application_date', now()->month)->count(),
        ];

        $branches = Branch::where('is_active', true)->get();

        return view('loans.loans.index', compact('loans', 'stats', 'branches'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $branches = Branch::where('is_active', true)->get();
        $loanOfficers = User::whereHas('roles', function($query) {
            $query->where('slug', 'loan-officer');
        })->where('is_active', true)->get();

        return view('loans.loans.create', compact('customers', 'branches', 'loanOfficers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'required|exists:branches,id',
            'loan_officer_id' => 'required|exists:users,id',
            'loan_type' => 'required|in:personal,business,mortgage,auto,education,home_equity',
            'amount' => 'required|numeric|min:100|max:1000000',
            'interest_rate' => 'required|numeric|min:0|max:50',
            'term_months' => 'required|integer|min:1|max:360',
            'purpose' => 'required|string|max:1000',
            'collateral_description' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate monthly payment
        $monthlyRate = $validated['interest_rate'] / 100 / 12;
        $monthlyPayment = $validated['amount'] * ($monthlyRate * pow(1 + $monthlyRate, $validated['term_months'])) / 
                         (pow(1 + $monthlyRate, $validated['term_months']) - 1);
        $totalPayment = $monthlyPayment * $validated['term_months'];

        $validated['monthly_payment'] = round($monthlyPayment, 2);
        $validated['total_payment'] = round($totalPayment, 2);
        $validated['outstanding_balance'] = $validated['amount'];
        $validated['application_date'] = now()->toDateString();
        $validated['created_by'] = auth()->id();

        $loan = Loan::create($validated);

        // Log activity
        auth()->user()->logActivity('loan', "Created new loan application for customer: {$loan->customer->full_name}");

        return redirect()->route('loans.index')
            ->with('success', 'Loan application created successfully.');
    }

    public function show(Loan $loan)
    {
        $loan->load(['customer', 'branch', 'loanOfficer', 'approver', 'disbursedBy', 'repayments' => function($query) {
            $query->latest()->limit(12);
        }]);

        $stats = [
            'total_repaid' => $loan->total_repaid,
            'remaining_balance' => $loan->remaining_balance,
            'remaining_term' => $loan->remaining_term,
            'days_past_due' => $loan->days_past_due,
            'is_overdue' => $loan->isOverdue(),
            'next_payment_date' => $loan->first_payment_date ? 
                $loan->first_payment_date->copy()->addMonths($loan->term_months - $loan->remaining_term) : null,
        ];

        return view('loans.loans.show', compact('loan', 'stats'));
    }

    public function edit(Loan $loan)
    {
        $loan->load(['customer', 'branch', 'loanOfficer']);
        $customers = Customer::where('is_active', true)->get();
        $branches = Branch::where('is_active', true)->get();
        $loanOfficers = User::whereHas('roles', function($query) {
            $query->where('slug', 'loan-officer');
        })->where('is_active', true)->get();

        return view('loans.loans.edit', compact('loan', 'customers', 'branches', 'loanOfficers'));
    }

    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'required|exists:branches,id',
            'loan_officer_id' => 'required|exists:users,id',
            'loan_type' => 'required|in:personal,business,mortgage,auto,education,home_equity',
            'amount' => 'required|numeric|min:100|max:1000000',
            'interest_rate' => 'required|numeric|min:0|max:50',
            'term_months' => 'required|integer|min:1|max:360',
            'purpose' => 'required|string|max:1000',
            'collateral_description' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Recalculate payments if amount or terms changed
        if ($validated['amount'] !== $loan->amount || $validated['interest_rate'] !== $loan->interest_rate || $validated['term_months'] !== $loan->term_months) {
            $monthlyRate = $validated['interest_rate'] / 100 / 12;
            $monthlyPayment = $validated['amount'] * ($monthlyRate * pow(1 + $monthlyRate, $validated['term_months'])) / 
                             (pow(1 + $monthlyRate, $validated['term_months']) - 1);
            $totalPayment = $monthlyPayment * $validated['term_months'];

            $validated['monthly_payment'] = round($monthlyPayment, 2);
            $validated['total_payment'] = round($totalPayment, 2);
        }

        $validated['updated_by'] = auth()->id();

        $loan->update($validated);

        // Log activity
        auth()->user()->logActivity('loan', "Updated loan for customer: {$loan->customer->full_name}");

        return redirect()->route('loans.index')
            ->with('success', 'Loan updated successfully.');
    }

    public function destroy(Loan $loan)
    {
        if (in_array($loan->status, ['disbursed', 'active'])) {
            return redirect()->route('loans.index')
                ->with('error', 'Cannot delete disbursed or active loans.');
        }

        $customerName = $loan->customer->full_name;
        $loan->delete();

        // Log activity
        auth()->user()->logActivity('loan', "Deleted loan for customer: {$customerName}");

        return redirect()->route('loans.index')
            ->with('success', 'Loan deleted successfully.');
    }

    public function approval()
    {
        $query = Loan::with(['customer', 'branch', 'loanOfficer'])
                     ->whereIn('status', ['pending', 'approved']);

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('branch_id')) {
            $query->where('branch_id', request('branch_id'));
        }

        if (request('loan_officer_id')) {
            $query->where('loan_officer_id', request('loan_officer_id'));
        }

        $applications = $query->latest()->paginate(20);

        $stats = [
            'total_applications' => Loan::whereIn('status', ['pending', 'approved'])->count(),
            'pending_review' => Loan::where('status', 'pending')->count(),
            'approved_today' => Loan::where('status', 'approved')->whereDate('approval_date', today())->count(),
            'avg_processing_time' => '2.3 days', // This would be calculated from actual data
        ];

        $branches = \App\Models\Branch::all();
        $loanOfficers = \App\Models\User::whereHas('roles', function($query) {
            $query->where('slug', 'loan-officer');
        })->get();

        return view('loans.approval.index', compact('applications', 'stats', 'branches', 'loanOfficers'));
    }

    public function applications()
    {
        $query = Loan::with(['customer', 'branch', 'loanOfficer'])
                     ->whereIn('status', ['pending', 'approved']);

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $applications = $query->latest()->paginate(20);

        $stats = [
            'total_applications' => Loan::whereIn('status', ['pending', 'approved'])->count(),
            'pending_review' => Loan::where('status', 'pending')->count(),
            'awaiting_disbursement' => Loan::where('status', 'approved')->count(),
            'avg_application_amount' => Loan::whereIn('status', ['pending', 'approved'])->avg('amount'),
        ];

        return view('loans.applications.index', compact('applications', 'stats'));
    }

    public function approve(Request $request, Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending applications can be approved.');
        }

        $validated = $request->validate([
            'approved_amount' => 'required|numeric|min:100|max:' . $loan->amount,
            'approved_interest_rate' => 'required|numeric|min:0|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Recalculate with approved terms
        $monthlyRate = $validated['approved_interest_rate'] / 100 / 12;
        $monthlyPayment = $validated['approved_amount'] * ($monthlyRate * pow(1 + $monthlyRate, $loan->term_months)) / 
                         (pow(1 + $monthlyRate, $loan->term_months) - 1);
        $totalPayment = $monthlyPayment * $loan->term_months;

        $loan->update([
            'amount' => $validated['approved_amount'],
            'interest_rate' => $validated['approved_interest_rate'],
            'monthly_payment' => round($monthlyPayment, 2),
            'total_payment' => round($totalPayment, 2),
            'outstanding_balance' => $validated['approved_amount'],
            'status' => 'approved',
            'approval_date' => now()->toDateString(),
            'approved_by' => auth()->id(),
            'notes' => $validated['notes'] ?? $loan->notes,
        ]);

        // Log activity
        auth()->user()->logActivity('loan', "Approved loan for customer: {$loan->customer->full_name}");

        return redirect()->route('loans.applications')
            ->with('success', 'Loan approved successfully.');
    }

    public function reject(Request $request, Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending applications can be rejected.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $loan->update([
            'status' => 'rejected',
            'notes' => "Rejected: " . $validated['rejection_reason'],
        ]);

        // Log activity
        auth()->user()->logActivity('loan', "Rejected loan for customer: {$loan->customer->full_name}");

        return redirect()->route('loans.applications')
            ->with('success', 'Loan rejected successfully.');
    }

    public function disbursement()
    {
        $query = Loan::with(['customer', 'branch', 'loanOfficer'])
                     ->where('status', 'approved');

        if (request('branch_id')) {
            $query->where('branch_id', request('branch_id'));
        }

        $loans = $query->latest()->paginate(20);

        $stats = [
            'ready_for_disbursement' => Loan::where('status', 'approved')->count(),
            'total_disbursement_amount' => Loan::where('status', 'approved')->sum('amount'),
            'disbursed_today' => Loan::whereDate('disbursement_date', today())->count(),
            'disbursed_this_month' => Loan::whereMonth('disbursement_date', now()->month)->count(),
        ];

        $branches = Branch::where('is_active', true)->get();

        return view('loans.disbursement.index', compact('loans', 'stats', 'branches'));
    }

    public function disburse(Request $request, Loan $loan)
    {
        if ($loan->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved loans can be disbursed.');
        }

        $validated = $request->validate([
            'disbursement_method' => 'required|in:bank_transfer,cash,check',
            'disbursement_reference' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $loan->update([
            'status' => 'disbursed',
            'disbursement_date' => now()->toDateString(),
            'first_payment_date' => now()->addMonth()->toDateString(),
            'maturity_date' => now()->addMonths($loan->term_months)->toDateString(),
            'disbursed_by' => auth()->id(),
            'notes' => ($loan->notes ?? '') . "\nDisbursement: {$validated['disbursement_method']} - {$validated['disbursement_reference']}",
        ]);

        // Log activity
        auth()->user()->logActivity('loan', "Disbursed loan for customer: {$loan->customer->full_name}");

        return redirect()->route('loans.disbursement')
            ->with('success', 'Loan disbursed successfully.');
    }

    public function repayments()
    {
        $query = Loan::with(['customer', 'branch', 'repayments' => function($query) {
            $query->latest()->limit(6);
        }])->whereIn('status', ['active', 'disbursed']);

        if (request('status')) {
            if (request('status') === 'overdue') {
                $query->whereHas('repayments', function($q) {
                    $q->where('payment_date', '<', now());
                });
            }
        }

        $loans = $query->latest()->paginate(20);

        $stats = [
            'active_loans' => Loan::whereIn('status', ['active', 'disbursed'])->count(),
            'overdue_loans' => Loan::whereIn('status', ['active', 'disbursed'])
                                ->whereHas('repayments', function($q) {
                                    $q->where('payment_date', '<', now());
                                })->count(),
            'total_outstanding' => Loan::whereIn('status', ['active', 'disbursed'])->sum('outstanding_balance'),
            'payments_this_month' => \App\Models\LoanRepayment::whereMonth('payment_date', now()->month)->sum('amount'),
        ];

        return view('loans.repayments.index', compact('loans', 'stats'));
    }
}
