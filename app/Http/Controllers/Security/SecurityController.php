<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function audit()
    {
        $stats = [
            'total_logs' => 15420,
            'logs_today' => 1250,
            'suspicious_activities' => 3,
            'retention_days' => 90,
        ];

        $logs = [
            [
                'id' => 1,
                'user' => 'John Doe',
                'action' => 'Loan Disbursement',
                'ip' => '192.168.1.100',
                'timestamp' => '2024-03-20 10:30:00',
                'status' => 'success'
            ],
            [
                'id' => 2,
                'user' => 'Jane Smith',
                'action' => 'Customer Data Access',
                'ip' => '192.168.1.101',
                'timestamp' => '2024-03-20 10:25:00',
                'status' => 'success'
            ],
            [
                'id' => 3,
                'user' => 'Unknown',
                'action' => 'Failed Login Attempt',
                'ip' => '192.168.1.200',
                'timestamp' => '2024-03-20 10:20:00',
                'status' => 'suspicious'
            ],
        ];

        return view('security.audit', compact('stats', 'logs'));
    }

    public function fraud()
    {
        $stats = [
            'alerts_today' => 8,
            'investigations' => 3,
            'blocked_transactions' => 2,
            'false_positives' => 15,
        ];

        $alerts = [
            [
                'id' => 1,
                'type' => 'Unusual Transaction Pattern',
                'severity' => 'high',
                'user' => 'Customer #1234',
                'detected' => '2024-03-20 09:45:00',
                'status' => 'investigating'
            ],
            [
                'id' => 2,
                'type' => 'Multiple Failed Logins',
                'severity' => 'medium',
                'user' => 'Customer #5678',
                'detected' => '2024-03-20 09:30:00',
                'status' => 'resolved'
            ],
            [
                'id' => 3,
                'type' => 'Suspicious IP Address',
                'severity' => 'low',
                'user' => 'Multiple',
                'detected' => '2024-03-20 09:15:00',
                'status' => 'monitoring'
            ],
        ];

        return view('security.fraud', compact('stats', 'alerts'));
    }

    public function aml()
    {
        $stats = [
            'screenings_today' => 45,
            'matches_found' => 2,
            'reports_generated' => 1,
            'compliance_rate' => 99.8,
        ];

        $reports = [
            [
                'id' => 1,
                'customer' => 'Customer #1234',
                'risk_level' => 'medium',
                'screening_date' => '2024-03-20',
                'status' => 'review_required'
            ],
            [
                'id' => 2,
                'customer' => 'Customer #5678',
                'risk_level' => 'low',
                'screening_date' => '2024-03-20',
                'status' => 'cleared'
            ],
        ];

        return view('security.aml', compact('stats', 'reports'));
    }
}
