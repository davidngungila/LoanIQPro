<?php

namespace App\Http\Controllers\Disbursements;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Branch;
use Illuminate\Http\Request;

class DisbursementController extends Controller
{
    public function queue()
    {
        $query = Loan::with(['customer', 'branch', 'loanOfficer'])
                     ->where('status', 'approved');

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
            'ready_for_disbursement' => Loan::where('status', 'approved')->count(),
            'total_disbursement_amount' => Loan::where('status', 'approved')->sum('amount'),
            'disbursed_today' => Loan::where('status', 'disbursed')->whereDate('disbursement_date', today())->count(),
            'disbursed_this_month' => Loan::where('status', 'disbursed')->whereMonth('disbursement_date', now()->month)->count(),
        ];

        $branches = Branch::all();

        return view('disbursements.queue', compact('loans', 'stats', 'branches'));
    }

    public function bulk()
    {
        $query = Loan::with(['customer', 'branch', 'loanOfficer'])
                     ->where('status', 'approved');

        if (request('branch_id')) {
            $query->where('branch_id', request('branch_id'));
        }

        $loans = $query->latest()->paginate(20);
        $branches = Branch::all();

        $stats = [
            'ready_for_bulk' => Loan::where('status', 'approved')->count(),
            'total_bulk_amount' => Loan::where('status', 'approved')->sum('amount'),
            'bulk_processed_today' => 0, // This would track bulk disbursements
            'avg_bulk_size' => 5, // This would be calculated
        ];

        return view('disbursements.bulk', compact('loans', 'stats', 'branches'));
    }

    public function bulkProcess(Request $request)
    {
        $validated = $request->validate([
            'loan_ids' => 'required|array',
            'loan_ids.*' => 'exists:loans,id',
            'disbursement_method' => 'required|string',
            'batch_reference' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $loans = Loan::whereIn('id', $validated['loan_ids'])->get();
        $processedCount = 0;

        foreach ($loans as $loan) {
            if ($loan->status === 'approved') {
                $loan->update([
                    'status' => 'disbursed',
                    'disbursement_date' => now(),
                    'disbursement_method' => $validated['disbursement_method'],
                    'disbursement_reference' => $validated['batch_reference'],
                ]);
                $processedCount++;
            }
        }

        // Log activity
        auth()->user()->logActivity('disbursement', "Processed bulk disbursement of {$processedCount} loans with reference: {$validated['batch_reference']}");

        return redirect()->route('disbursements.bulk')
            ->with('success', "Successfully processed {$processedCount} loan disbursements.");
    }
}
