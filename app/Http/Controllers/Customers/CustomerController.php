<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['loans' => function($query) {
            $query->latest()->limit(3);
        }]);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kyc_status')) {
            $query->where('kyc_status', $request->kyc_status);
        }

        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        if ($request->filled('min_income')) {
            $query->where('monthly_income', '>=', $request->min_income);
        }

        if ($request->filled('max_income')) {
            $query->where('monthly_income', '<=', $request->max_income);
        }

        if ($request->filled('min_credit_score')) {
            $query->where('credit_score', '>=', $request->min_credit_score);
        }

        if ($request->filled('max_credit_score')) {
            $query->where('credit_score', '<=', $request->max_credit_score);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $customers = $query->latest()->paginate(20);

        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('is_active', true)->count(),
            'kyc_verified' => Customer::where('kyc_status', 'verified')->count(),
            'kyc_pending' => Customer::where('kyc_status', 'pending')->count(),
            'kyc_rejected' => Customer::where('kyc_status', 'rejected')->count(),
            'new_this_month' => Customer::whereMonth('created_at', now()->month)->count(),
            'avg_credit_score' => Customer::avg('credit_score'),
            'total_loan_portfolio' => Loan::sum('amount'),
            'active_loans' => Loan::where('status', 'active')->count(),
        ];

        return view('customers.customers.index', compact('customers', 'stats'));
    }

    public function create()
    {
        return view('customers.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'national_id' => 'required|string|max:50|unique:customers',
            'passport_number' => 'nullable|string|max:50',
            'employment_status' => 'required|in:employed,self-employed,unemployed,student,retired',
            'employer_name' => 'nullable|string|max:255',
            'monthly_income' => 'required|numeric|min:0',
            'credit_score' => 'nullable|integer|min:0|max:850',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['kyc_status'] = 'pending';
        $validated['created_by'] = auth()->id();

        $customer = Customer::create($validated);

        // Log activity
        auth()->user()->logActivity('customer', "Created new customer: {$customer->full_name}");

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['loans' => function($query) {
            $query->with(['branch', 'loanOfficer'])->latest();
        }, 'kycVerifier']);

        $stats = [
            'total_loans' => $customer->loans->count(),
            'active_loans' => $customer->active_loans->count(),
            'completed_loans' => $customer->completed_loans->count(),
            'total_loan_amount' => $customer->total_loan_amount,
            'outstanding_balance' => $customer->outstanding_loan_amount,
            'account_age' => $customer->created_at->diffForHumans(),
            'age' => $customer->age,
        ];

        return view('customers.customers.show', compact('customer', 'stats'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'national_id' => ['required', 'string', 'max:50', Rule::unique('customers')->ignore($customer->id)],
            'passport_number' => 'nullable|string|max:50',
            'employment_status' => 'required|in:employed,self-employed,unemployed,student,retired',
            'employer_name' => 'nullable|string|max:255',
            'monthly_income' => 'required|numeric|min:0',
            'credit_score' => 'nullable|integer|min:0|max:850',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['updated_by'] = auth()->id();

        $customer->update($validated);

        // Log activity
        auth()->user()->logActivity('customer', "Updated customer: {$customer->full_name}");

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        // Check if customer has active loans
        if ($customer->active_loans()->count() > 0) {
            return redirect()->route('customers.index')
                ->with('error', 'Cannot delete customer with active loans.');
        }

        $customerName = $customer->full_name;
        $customer->delete();

        // Log activity
        auth()->user()->logActivity('customer', "Deleted customer: {$customerName}");

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function toggleStatus(Customer $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);
        $status = $customer->is_active ? 'activated' : 'deactivated';

        // Log activity
        auth()->user()->logActivity('customer', "{$status} customer: {$customer->full_name}");

        return response()->json([
            'success' => true,
            'message' => "Customer {$status} successfully.",
            'is_active' => $customer->is_active
        ]);
    }

    public function kyc()
    {
        $query = Customer::with(['kycVerifier']);

        if (request('status')) {
            $query->where('kyc_status', request('status'));
        }

        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(20);

        $stats = [
            'total_pending' => Customer::where('kyc_status', 'pending')->count(),
            'total_verified' => Customer::where('kyc_status', 'verified')->count(),
            'total_rejected' => Customer::where('kyc_status', 'rejected')->count(),
            'verification_rate' => Customer::count() > 0 ? round((Customer::where('kyc_status', 'verified')->count() / Customer::count()) * 100, 2) : 0,
        ];

        return view('customers.kyc.index', compact('customers', 'stats'));
    }

    public function verifyKyc(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'status' => 'required|in:verified,rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        $customer->update([
            'kyc_status' => $validated['status'],
            'kyc_verified_at' => now(),
            'kyc_verified_by' => auth()->id(),
            'notes' => $validated['notes'] ?? $customer->notes,
        ]);

        // Log activity
        auth()->user()->logActivity('kyc', "KYC {$validated['status']} for customer: {$customer->full_name}");

        return redirect()->route('customers.kyc')
            ->with('success', "KYC {$validated['status']} successfully.");
    }

    public function segmentation()
    {
        $segments = [
            'by_income' => $this->getIncomeSegmentation(),
            'by_credit_score' => $this->getCreditScoreSegmentation(),
            'by_employment' => $this->getEmploymentSegmentation(),
            'by_loan_history' => $this->getLoanHistorySegmentation(),
            'by_age' => $this->getAgeSegmentation(),
        ];

        return view('customers.segmentation.index', compact('segments'));
    }

    private function getIncomeSegmentation()
    {
        return [
            'low_income' => Customer::where('monthly_income', '<', 1000)->count(),
            'middle_income' => Customer::whereBetween('monthly_income', [1000, 5000])->count(),
            'high_income' => Customer::where('monthly_income', '>', 5000)->count(),
            'avg_income' => Customer::avg('monthly_income'),
        ];
    }

    private function getCreditScoreSegmentation()
    {
        return [
            'excellent' => Customer::where('credit_score', '>=', 750)->count(),
            'good' => Customer::whereBetween('credit_score', [700, 749])->count(),
            'fair' => Customer::whereBetween('credit_score', [650, 699])->count(),
            'poor' => Customer::where('credit_score', '<', 650)->count(),
            'avg_score' => Customer::avg('credit_score'),
        ];
    }

    private function getEmploymentSegmentation()
    {
        return Customer::selectRaw('employment_status, count(*) as count')
            ->groupBy('employment_status')
            ->get()
            ->pluck('count', 'employment_status')
            ->toArray();
    }

    private function getLoanHistorySegmentation()
    {
        return [
            'no_loans' => Customer::whereDoesntHave('loans')->count(),
            'first_time' => Customer::whereHas('loans')->whereDoesntHave('loans', function($q) {
                $q->where('status', 'completed');
            })->count(),
            'repeat_customers' => Customer::whereHas('loans')->whereHas('loans', function($q) {
                $q->where('status', 'completed');
            })->count(),
        ];
    }

    private function getAgeSegmentation()
    {
        return [
            '18-25' => Customer::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 25')->count(),
            '26-35' => Customer::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 35')->count(),
            '36-45' => Customer::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 45')->count(),
            '46-55' => Customer::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 46 AND 55')->count(),
            '55+' => Customer::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) > 55')->count(),
        ];
    }
}
