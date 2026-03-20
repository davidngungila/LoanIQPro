<?php

namespace App\Http\Controllers\Integrations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function payments()
    {
        $stats = [
            'active_gateways' => 3,
            'total_transactions' => 1250,
            'success_rate' => 98.5,
            'monthly_volume' => 2500000,
        ];

        $gateways = [
            [
                'name' => 'M-Pesa',
                'status' => 'active',
                'transactions' => 450,
                'success_rate' => 99.2,
                'last_sync' => '2024-03-20 10:30'
            ],
            [
                'name' => 'Bank Transfer',
                'status' => 'active',
                'transactions' => 320,
                'success_rate' => 97.8,
                'last_sync' => '2024-03-20 09:15'
            ],
            [
                'name' => 'Mobile Money',
                'status' => 'active',
                'transactions' => 280,
                'success_rate' => 98.1,
                'last_sync' => '2024-03-20 11:00'
            ],
        ];

        return view('integrations.payments', compact('stats', 'gateways'));
    }

    public function communication()
    {
        $stats = [
            'sms_sent' => 2500,
            'emails_sent' => 1800,
            'delivery_rate' => 96.5,
            'active_campaigns' => 5,
        ];

        $campaigns = [
            [
                'name' => 'Payment Reminders',
                'type' => 'SMS',
                'sent' => 850,
                'delivered' => 820,
                'status' => 'active'
            ],
            [
                'name' => 'Loan Approvals',
                'type' => 'Email',
                'sent' => 320,
                'delivered' => 315,
                'status' => 'active'
            ],
            [
                'name' => 'Monthly Statements',
                'type' => 'Email',
                'sent' => 450,
                'delivered' => 435,
                'status' => 'completed'
            ],
        ];

        return view('integrations.communication', compact('stats', 'campaigns'));
    }

    public function credit()
    {
        $stats = [
            'active_bureaus' => 2,
            'checks_today' => 45,
            'api_calls' => 1250,
            'response_time' => 1.2,
        ];

        $bureaus = [
            [
                'name' => 'Credit Reference Bureau',
                'status' => 'active',
                'checks_today' => 25,
                'success_rate' => 99.5,
                'last_sync' => '2024-03-20 10:45'
            ],
            [
                'name' => 'Metropol CRB',
                'status' => 'active',
                'checks_today' => 20,
                'success_rate' => 98.8,
                'last_sync' => '2024-03-20 10:30'
            ],
        ];

        return view('integrations.credit', compact('stats', 'bureaus'));
    }
}
