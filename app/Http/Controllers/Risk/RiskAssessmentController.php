<?php

namespace App\Http\Controllers\Risk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Customer;

class RiskAssessmentController extends Controller
{
    public function creditScoring()
    {
        $stats = [
            'total_assessments' => 1250,
            'average_score' => 685,
            'high_risk_count' => 45,
            'low_risk_count' => 890,
        ];

        $assessments = [
            [
                'id' => 1,
                'customer' => 'John Doe',
                'loan_amount' => 50000,
                'credit_score' => 720,
                'risk_level' => 'low',
                'assessment_date' => '2024-03-20',
                'ai_score' => 715,
                'manual_score' => 725,
                'status' => 'approved'
            ],
            [
                'id' => 2,
                'customer' => 'Jane Smith',
                'loan_amount' => 75000,
                'credit_score' => 580,
                'risk_level' => 'medium',
                'assessment_date' => '2024-03-20',
                'ai_score' => 575,
                'manual_score' => 585,
                'status' => 'pending'
            ],
            [
                'id' => 3,
                'customer' => 'Mike Johnson',
                'loan_amount' => 100000,
                'credit_score' => 420,
                'risk_level' => 'high',
                'assessment_date' => '2024-03-19',
                'ai_score' => 415,
                'manual_score' => 425,
                'status' => 'rejected'
            ],
        ];

        $scoringFactors = [
            'payment_history' => 35,
            'credit_utilization' => 30,
            'loan_history' => 15,
            'income_stability' => 10,
            'employment_length' => 5,
            'existing_debts' => 5,
        ];

        return view('risk.credit-scoring', compact('stats', 'assessments', 'scoringFactors'));
    }

    public function riskAnalysis()
    {
        $stats = [
            'portfolio_at_risk' => 8.5,
            'default_rate' => 2.3,
            'prediction_accuracy' => 94.2,
            'high_risk_loans' => 127,
        ];

        $riskTrends = [
            'jan' => ['par' => 7.2, 'defaults' => 1.8],
            'feb' => ['par' => 7.8, 'defaults' => 2.1],
            'mar' => ['par' => 8.5, 'defaults' => 2.3],
            'apr' => ['par' => 8.2, 'defaults' => 2.0],
            'may' => ['par' => 7.9, 'defaults' => 1.9],
            'jun' => ['par' => 8.1, 'defaults' => 2.2],
        ];

        $predictions = [
            [
                'loan_id' => 'LN001234',
                'customer' => 'John Doe',
                'current_risk' => 'medium',
                'predicted_default' => 15,
                'confidence' => 87,
                'recommended_action' => 'monitor',
                'factors' => ['late payments', 'high utilization']
            ],
            [
                'loan_id' => 'LN001235',
                'customer' => 'Jane Smith',
                'current_risk' => 'low',
                'predicted_default' => 5,
                'confidence' => 92,
                'recommended_action' => 'maintain',
                'factors' => ['stable income', 'good payment history']
            ],
        ];

        return view('risk.risk-analysis', compact('stats', 'riskTrends', 'predictions'));
    }

    public function portfolioRisk()
    {
        $stats = [
            'total_portfolio' => 25000000,
            'at_risk_amount' => 2125000,
            'par_percentage' => 8.5,
            'critical_loans' => 45,
        ];

        $parByBranch = [
            'Nairobi' => ['portfolio' => 10000000, 'at_risk' => 850000, 'par' => 8.5],
            'Mombasa' => ['portfolio' => 7500000, 'at_risk' => 637500, 'par' => 8.5],
            'Kisumu' => ['portfolio' => 5000000, 'at_risk' => 425000, 'par' => 8.5],
            'Eldoret' => ['portfolio' => 2500000, 'at_risk' => 212500, 'par' => 8.5],
        ];

        $parByLoanType = [
            'Personal Loan' => ['total' => 15000000, 'at_risk' => 1275000, 'par' => 8.5],
            'Business Loan' => ['total' => 7500000, 'at_risk' => 637500, 'par' => 8.5],
            'Mortgage' => ['total' => 2500000, 'at_risk' => 212500, 'par' => 8.5],
        ];

        $agingReport = [
            '1-30 days' => ['count' => 125, 'amount' => 1250000, 'percentage' => 5.0],
            '31-60 days' => ['count' => 67, 'amount' => 670000, 'percentage' => 2.68],
            '61-90 days' => ['count' => 35, 'amount' => 350000, 'percentage' => 1.4],
            '90+ days' => ['count' => 45, 'amount' => 450000, 'percentage' => 1.8],
        ];

        return view('risk.portfolio-risk', compact('stats', 'parByBranch', 'parByLoanType', 'agingReport'));
    }

    public function loanApproval()
    {
        $pendingApplications = [
            [
                'id' => 1,
                'customer' => 'John Doe',
                'loan_amount' => 50000,
                'credit_score' => 720,
                'risk_level' => 'low',
                'recommendation' => 'approve',
                'confidence' => 95,
                'dti_ratio' => 28,
                'employment_status' => 'employed',
                'monthly_income' => 8500,
            ],
            [
                'id' => 2,
                'customer' => 'Jane Smith',
                'loan_amount' => 75000,
                'credit_score' => 580,
                'risk_level' => 'medium',
                'recommendation' => 'review',
                'confidence' => 72,
                'dti_ratio' => 42,
                'employment_status' => 'employed',
                'monthly_income' => 6500,
            ],
            [
                'id' => 3,
                'customer' => 'Mike Johnson',
                'loan_amount' => 100000,
                'credit_score' => 420,
                'risk_level' => 'high',
                'recommendation' => 'reject',
                'confidence' => 88,
                'dti_ratio' => 55,
                'employment_status' => 'self-employed',
                'monthly_income' => 4500,
            ],
        ];

        $approvalCriteria = [
            'min_credit_score' => 600,
            'max_dti_ratio' => 45,
            'min_employment_months' => 6,
            'min_income' => 3000,
        ];

        return view('risk.loan-approval', compact('pendingApplications', 'approvalCriteria'));
    }

    public function riskReports()
    {
        $defaultTrends = [
            'jan' => 1.8, 'feb' => 2.1, 'mar' => 2.3, 'apr' => 2.0, 'may' => 1.9, 'jun' => 2.2
        ];

        $overdueTrends = [
            'jan' => 5.2, 'feb' => 5.8, 'mar' => 6.5, 'apr' => 6.2, 'may' => 5.9, 'jun' => 6.1
        ];

        $branchPerformance = [
            'Nairobi' => ['defaults' => 2.1, 'par' => 8.2, 'recovery_rate' => 94.5],
            'Mombasa' => ['defaults' => 2.3, 'par' => 8.7, 'recovery_rate' => 93.2],
            'Kisumu' => ['defaults' => 2.0, 'par' => 8.0, 'recovery_rate' => 95.1],
            'Eldoret' => ['defaults' => 2.5, 'par' => 9.1, 'recovery_rate' => 92.8],
        ];

        return view('risk.risk-reports', compact('defaultTrends', 'overdueTrends', 'branchPerformance'));
    }

    public function performanceReports()
    {
        $officerPerformance = [
            [
                'name' => 'John Doe',
                'loans_processed' => 125,
                'approval_rate' => 78,
                'default_rate' => 1.8,
                'avg_loan_size' => 45000,
                'revenue_generated' => 125000,
            ],
            [
                'name' => 'Jane Smith',
                'loans_processed' => 98,
                'approval_rate' => 82,
                'default_rate' => 1.5,
                'avg_loan_size' => 52000,
                'revenue_generated' => 118000,
            ],
            [
                'name' => 'Mike Johnson',
                'loans_processed' => 87,
                'approval_rate' => 75,
                'default_rate' => 2.2,
                'avg_loan_size' => 38000,
                'revenue_generated' => 95000,
            ],
        ];

        $branchMetrics = [
            'Nairobi' => [
                'loans_disbursed' => 450,
                'total_amount' => 22500000,
                'approval_rate' => 79,
                'default_rate' => 2.1,
                'revenue' => 1250000,
            ],
            'Mombasa' => [
                'loans_disbursed' => 320,
                'total_amount' => 16000000,
                'approval_rate' => 76,
                'default_rate' => 2.3,
                'revenue' => 890000,
            ],
        ];

        return view('risk.performance-reports', compact('officerPerformance', 'branchMetrics'));
    }
}
