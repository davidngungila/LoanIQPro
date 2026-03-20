<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function loans()
    {
        $settings = [
            'min_loan_amount' => 1000,
            'max_loan_amount' => 500000,
            'default_interest_rate' => 15.5,
            'max_term_months' => 60,
            'processing_fee_rate' => 2.5,
        ];

        $loanTypes = [
            [
                'name' => 'Personal Loan',
                'min_amount' => 1000,
                'max_amount' => 100000,
                'interest_rate' => 18.5,
                'max_term' => 36,
                'status' => 'active'
            ],
            [
                'name' => 'Business Loan',
                'min_amount' => 10000,
                'max_amount' => 500000,
                'interest_rate' => 15.5,
                'max_term' => 60,
                'status' => 'active'
            ],
            [
                'name' => 'Mortgage',
                'min_amount' => 50000,
                'max_amount' => 1000000,
                'interest_rate' => 12.5,
                'max_term' => 240,
                'status' => 'active'
            ],
        ];

        return view('settings.loans', compact('settings', 'loanTypes'));
    }

    public function interest()
    {
        $settings = [
            'default_rate' => 15.5,
            'penalty_rate' => 5.0,
            'late_fee_amount' => 500,
            'grace_period_days' => 5,
            'compound_frequency' => 'monthly',
        ];

        $rateTiers = [
            [
                'credit_score_min' => 750,
                'credit_score_max' => 850,
                'interest_rate' => 12.5,
                'description' => 'Excellent Credit'
            ],
            [
                'credit_score_min' => 650,
                'credit_score_max' => 749,
                'interest_rate' => 15.5,
                'description' => 'Good Credit'
            ],
            [
                'credit_score_min' => 550,
                'credit_score_max' => 649,
                'interest_rate' => 18.5,
                'description' => 'Fair Credit'
            ],
            [
                'credit_score_min' => 300,
                'credit_score_max' => 549,
                'interest_rate' => 22.5,
                'description' => 'Poor Credit'
            ],
        ];

        return view('settings.interest', compact('settings', 'rateTiers'));
    }

    public function backup()
    {
        $stats = [
            'last_backup' => '2024-03-20 02:00:00',
            'backup_size' => '2.5 GB',
            'total_backups' => 30,
            'retention_days' => 90,
        ];

        $backups = [
            [
                'id' => 1,
                'filename' => 'backup_2024_03_20_02_00.sql',
                'size' => '2.5 GB',
                'created_at' => '2024-03-20 02:00:00',
                'status' => 'completed'
            ],
            [
                'id' => 2,
                'filename' => 'backup_2024_03_19_02_00.sql',
                'size' => '2.4 GB',
                'created_at' => '2024-03-19 02:00:00',
                'status' => 'completed'
            ],
            [
                'id' => 3,
                'filename' => 'backup_2024_03_18_02_00.sql',
                'size' => '2.3 GB',
                'created_at' => '2024-03-18 02:00:00',
                'status' => 'completed'
            ],
        ];

        return view('settings.backup', compact('stats', 'backups'));
    }
}
