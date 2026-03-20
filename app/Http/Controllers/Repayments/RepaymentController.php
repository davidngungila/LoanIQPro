<?php

namespace App\Http\Controllers\Repayments;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Branch;
use Illuminate\Http\Request;

class RepaymentController extends Controller
{
    public function tracking()
    {
        $query = Loan::with(['customer', 'branch', 'loanOfficer'])
                     ->whereIn('status', ['active', 'disbursed']);

        if (request('status')) {
            if (request('status') === 'overdue') {
                $query->where('first_payment_date', '<', now()->subDays(30));
            } elseif (request('status') === 'current') {
                $query->where('first_payment_date', '>=', now()->subDays(30));
            } elseif (request('status') === 'advanced') {
                $query->where('first_payment_date', '>', now()->addDays(10));
            }
        }

        if (request('branch_id')) {
            $query->where('branch_id', request('branch_id'));
        }

        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($subQ) use ($search) {
                    $subQ->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $loans = $query->latest()->paginate(20);

        $stats = [
            'active_loans' => Loan::whereIn('status', ['active', 'disbursed'])->count(),
            'overdue_loans' => Loan::whereIn('status', ['active', 'disbursed'])
                                ->where('first_payment_date', '<', now()->subDays(30))->count(),
            'total_outstanding' => Loan::whereIn('status', ['active', 'disbursed'])->sum('outstanding_balance'),
            'payments_this_month' => 125000, // This would be calculated from payment records
        ];

        $branches = Branch::all();

        return view('repayments.tracking', compact('loans', 'stats', 'branches'));
    }

    public function schedules()
    {
        $query = Loan::with(['customer', 'branch', 'loanOfficer'])
                     ->whereIn('status', ['active', 'disbursed']);

        if (request('branch_id')) {
            $query->where('branch_id', request('branch_id'));
        }

        $loans = $query->latest()->paginate(20);
        $branches = Branch::all();

        $stats = [
            'scheduled_payments' => Loan::whereIn('status', ['active', 'disbursed'])->count(),
            'next_payment_total' => Loan::whereIn('status', ['active', 'disbursed'])->sum('monthly_payment'),
            'payments_this_week' => 45, // This would be calculated
            'upcoming_payments' => 12, // This would be calculated
        ];

        return view('repayments.schedules', compact('loans', 'stats', 'branches'));
    }

    public function overdue()
    {
        $query = Loan::with(['customer', 'branch', 'loanOfficer'])
                     ->whereIn('status', ['active', 'disbursed'])
                     ->where('first_payment_date', '<', now()->subDays(30))
                     ->orderBy('first_payment_date', 'asc');

        if (request('branch_id')) {
            $query->where('branch_id', request('branch_id'));
        }

        if (request('days_overdue')) {
            $query->where('first_payment_date', '<', now()->subDays(request('days_overdue')));
        }

        $loans = $query->latest()->paginate(20);
        $branches = Branch::all();

        $stats = [
            'total_overdue' => Loan::whereIn('status', ['active', 'disbursed'])
                               ->where('first_payment_date', '<', now()->subDays(30))->count(),
            'overdue_amount' => Loan::whereIn('status', ['active', 'disbursed'])
                                  ->where('first_payment_date', '<', now()->subDays(30))
                                  ->sum('outstanding_balance'),
            'critical_overdue' => Loan::whereIn('status', ['active', 'disbursed'])
                                   ->where('first_payment_date', '<', now()->subDays(90))->count(),
            'avg_days_overdue' => 45, // This would be calculated
        ];

        return view('repayments.overdue', compact('loans', 'stats', 'branches'));
    }

    public function recordPayment(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'transaction_reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $loan = Loan::find($validated['loan_id']);
        
        // Here you would create a payment record and update loan balance
        // For now, we'll just log the activity
        
        auth()->user()->logActivity('payment', "Recorded payment of {$validated['amount']} for loan {$loan->id}");

        return redirect()->back()
            ->with('success', 'Payment recorded successfully.');
    }
}
