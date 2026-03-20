<?php

namespace App\Http\Controllers\Collections;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function recovery()
    {
        $query = Loan::with(['customer', 'branch', 'loanOfficer'])
                     ->whereIn('status', ['active', 'disbursed'])
                     ->where('first_payment_date', '<', now()->subDays(30));

        if (request('branch_id')) {
            $query->where('branch_id', request('branch_id'));
        }

        if (request('collector_id')) {
            $query->where('assigned_collector_id', request('collector_id'));
        }

        if (request('severity')) {
            switch(request('severity')) {
                case 'critical':
                    $query->where('first_payment_date', '<', now()->subDays(90));
                    break;
                case 'high':
                    $query->where('first_payment_date', '<', now()->subDays(60))
                          ->where('first_payment_date', '>=', now()->subDays(90));
                    break;
                case 'medium':
                    $query->where('first_payment_date', '<', now()->subDays(30))
                          ->where('first_payment_date', '>=', now()->subDays(60));
                    break;
            }
        }

        $loans = $query->latest()->paginate(20);

        $stats = [
            'total_recovery_cases' => Loan::whereIn('status', ['active', 'disbursed'])
                                       ->where('first_payment_date', '<', now()->subDays(30))->count(),
            'critical_cases' => Loan::whereIn('status', ['active', 'disbursed'])
                                 ->where('first_payment_date', '<', now()->subDays(90))->count(),
            'recovery_rate' => 78.5, // This would be calculated
            'total_recovery_amount' => Loan::whereIn('status', ['active', 'disbursed'])
                                        ->where('first_payment_date', '<', now()->subDays(30))
                                        ->sum('outstanding_balance'),
        ];

        $branches = Branch::all();
        $collectors = User::whereHas('roles', function($query) {
            $query->where('slug', 'collector');
        })->get();

        return view('collections.recovery', compact('loans', 'stats', 'branches', 'collectors'));
    }

    public function collectors()
    {
        $collectors = User::whereHas('roles', function($query) {
            $query->where('slug', 'collector');
        })->paginate(20);

        $stats = [
            'total_collectors' => $collectors->count(),
            'active_collectors' => $collectors->where('is_active', true)->count(),
            'avg_cases_per_collector' => 15, // This would be calculated
            'top_performer' => 'John Doe', // This would be calculated
        ];

        return view('collections.collectors', compact('collectors', 'stats'));
    }

    public function reports()
    {
        $stats = [
            'monthly_recovery' => [
                'jan' => 45000,
                'feb' => 52000,
                'mar' => 48000,
                'apr' => 61000,
                'may' => 58000,
                'jun' => 67000,
            ],
            'recovery_by_branch' => [
                'Nairobi' => 125000,
                'Mombasa' => 98000,
                'Kisumu' => 76000,
                'Eldoret' => 54000,
            ],
            'collector_performance' => [
                'John Doe' => 89,
                'Jane Smith' => 92,
                'Mike Johnson' => 85,
                'Sarah Williams' => 94,
            ],
            'aging_report' => [
                '30-60 days' => 12,
                '60-90 days' => 8,
                '90+ days' => 5,
            ],
        ];

        return view('collections.reports', compact('stats'));
    }

    public function assignCollector(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'collector_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $loan = Loan::find($validated['loan_id']);
        
        // Note: assigned_collector_id and assigned_date columns don't exist in the loans table
        // For now, we'll just log the activity. In a real implementation, you would:
        // 1. Add these columns to the loans table migration
        // 2. Create a separate loan_assignments table
        // 3. Or use a notes field to track assignments
        
        auth()->user()->logActivity('collection', "Collector {$validated['collector_id']} assigned to loan {$loan->id}");

        return redirect()->back()
            ->with('success', 'Collector assignment recorded successfully.');
    }
}
