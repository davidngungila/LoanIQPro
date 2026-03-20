<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Branch;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_disbursed' => Loan::where('status', 'disbursed')->sum('amount'),
            'total_repaid' => 2450000, // This would be calculated from payment records
            'outstanding_portfolio' => Loan::whereIn('status', ['active', 'disbursed'])->sum('outstanding_balance'),
            'monthly_revenue' => 125000, // This would be calculated from interest payments
            'portfolio_at_risk' => 8.5, // This would be calculated
            'efficiency_ratio' => 72.3, // This would be calculated
        ];

        $recentTransactions = [
            ['id' => 'TRX001', 'type' => 'Disbursement', 'amount' => 50000, 'date' => '2024-03-20', 'status' => 'Completed'],
            ['id' => 'TRX002', 'type' => 'Payment', 'amount' => 2500, 'date' => '2024-03-20', 'status' => 'Completed'],
            ['id' => 'TRX003', 'type' => 'Disbursement', 'amount' => 75000, 'date' => '2024-03-19', 'status' => 'Completed'],
            ['id' => 'TRX004', 'type' => 'Payment', 'amount' => 1800, 'date' => '2024-03-19', 'status' => 'Completed'],
        ];

        return view('accounting.dashboard', compact('stats', 'recentTransactions'));
    }

    public function transactions()
    {
        $query = Loan::with(['customer', 'branch']);

        if (request('transaction_type')) {
            switch(request('transaction_type')) {
                case 'disbursement':
                    $query->where('status', 'disbursed');
                    break;
                case 'payment':
                    // This would query payment records
                    break;
            }
        }

        if (request('branch_id')) {
            $query->where('branch_id', request('branch_id'));
        }

        if (request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }

        if (request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        $transactions = $query->latest()->paginate(20);
        $branches = Branch::all();

        $stats = [
            'total_transactions' => 1250,
            'total_volume' => 5250000,
            'transactions_today' => 25,
            'volume_today' => 125000,
        ];

        return view('accounting.transactions', compact('transactions', 'stats', 'branches'));
    }

    public function reports()
    {
        $stats = [
            'monthly_performance' => [
                'jan' => ['disbursed' => 450000, 'repaid' => 420000, 'revenue' => 35000],
                'feb' => ['disbursed' => 520000, 'repaid' => 480000, 'revenue' => 42000],
                'mar' => ['disbursed' => 480000, 'repaid' => 460000, 'revenue' => 38000],
                'apr' => ['disbursed' => 610000, 'repaid' => 580000, 'revenue' => 51000],
                'may' => ['disbursed' => 580000, 'repaid' => 550000, 'revenue' => 47000],
                'jun' => ['disbursed' => 670000, 'repaid' => 620000, 'revenue' => 58000],
            ],
            'branch_performance' => [
                'Nairobi' => ['portfolio' => 1250000, 'revenue' => 125000, 'efficiency' => 85],
                'Mombasa' => ['portfolio' => 980000, 'revenue' => 98000, 'efficiency' => 82],
                'Kisumu' => ['portfolio' => 760000, 'revenue' => 76000, 'efficiency' => 88],
                'Eldoret' => ['portfolio' => 540000, 'revenue' => 54000, 'efficiency' => 79],
            ],
            'loan_type_performance' => [
                'Personal' => ['count' => 150, 'amount' => 2250000, 'default_rate' => 3.2],
                'Business' => ['count' => 85, 'amount' => 1700000, 'default_rate' => 4.5],
                'Mortgage' => ['count' => 25, 'amount' => 1250000, 'default_rate' => 1.8],
                'Auto' => ['count' => 45, 'amount' => 675000, 'default_rate' => 5.2],
            ],
        ];

        return view('accounting.reports', compact('stats'));
    }

    public function reconciliation()
    {
        $stats = [
            'pending_reconciliation' => 15,
            'reconciled_today' => 45,
            'discrepancies_found' => 3,
            'last_reconciliation' => '2024-03-19 16:30',
        ];

        $reconciliationItems = [
            [
                'id' => 1,
                'account' => 'Main Account',
                'account_type' => 'Checking',
                'period' => 'Mar 2024',
                'system_balance' => 50000,
                'bank_balance' => 50000,
                'difference' => 0,
                'status' => 'matched'
            ],
            [
                'id' => 2,
                'account' => 'Loan Disbursement',
                'account_type' => 'Operating',
                'period' => 'Mar 2024',
                'system_balance' => 2500,
                'bank_balance' => 2480,
                'difference' => 20,
                'status' => 'pending'
            ],
            [
                'id' => 3,
                'account' => 'Collections',
                'account_type' => 'Revenue',
                'period' => 'Mar 2024',
                'system_balance' => 75000,
                'bank_balance' => 75000,
                'difference' => 0,
                'status' => 'matched'
            ],
            [
                'id' => 4,
                'account' => 'Interest Income',
                'account_type' => 'Revenue',
                'period' => 'Mar 2024',
                'system_balance' => 1800,
                'bank_balance' => 1800,
                'difference' => 0,
                'status' => 'matched'
            ],
        ];

        $discrepancyTypes = [
            'Timing Differences' => 8,
            'Bank Fees' => 3,
            'Data Entry Errors' => 2,
            'Unidentified Items' => 1,
        ];

        $reconciliationHistory = [
            [
                'date' => '2024-03-20',
                'accounts_count' => 12,
                'status' => 'success'
            ],
            [
                'date' => '2024-03-19',
                'accounts_count' => 8,
                'status' => 'success'
            ],
            [
                'date' => '2024-03-18',
                'accounts_count' => 15,
                'status' => 'failed'
            ],
        ];

        return view('accounting.reconciliation', compact('stats', 'reconciliationItems', 'discrepancyTypes', 'reconciliationHistory'));
    }
}
