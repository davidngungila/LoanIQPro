<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();
        $highestRole = $user->roles()->orderBy('level', 'desc')->first();

        if (!$highestRole) {
            return view('dashboard.default');
        }

        return match($highestRole->slug) {
            'super-admin' => $this->superAdminDashboard(),
            'admin' => $this->adminDashboard(),
            'loan-officer' => $this->loanOfficerDashboard(),
            'accountant' => $this->accountantDashboard(),
            'collector' => $this->collectorDashboard(),
            'auditor' => $this->auditorDashboard(),
            'customer' => $this->customerDashboard(),
            'guarantor' => $this->guarantorDashboard(),
            'it-support' => $this->itSupportDashboard(),
            default => view('dashboard.default'),
        };
    }

    /**
     * Super Admin Dashboard
     */
    public function superAdminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_branches' => \App\Models\Branch::count(),
            'system_health' => 'Good',
            'recent_activities' => ActivityLog::with('user')->latest()->limit(10)->get(),
        ];

        // Log activity
        if (Auth::check()) {
            Auth::user()->logActivity('dashboard', 'Accessed super admin dashboard');
        }

        return view('dashboard.super-admin', compact('stats'));
    }

    /**
     * System KPIs Dashboard
     */
    public function systemKpis()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_branches' => \App\Models\Branch::count(),
            'total_roles' => \App\Models\Role::count(),
            'user_growth' => $this->getUserGrowth(),
            'branch_performance' => $this->getBranchPerformance(),
            'role_distribution' => $this->getRoleDistribution(),
            'system_health' => $this->getSystemHealth(),
            'recent_activities' => ActivityLog::with('user')->latest()->limit(10)->get(),
        ];

        // Log activity
        if (Auth::check()) {
            Auth::user()->logActivity('dashboard', 'Accessed system KPIs dashboard');
        }

        return view('dashboard.system-kpis', compact('stats'));
    }

    /**
     * Multi-branch Analytics Dashboard
     */
    public function multiBranchAnalytics()
    {
        $stats = [
            'branches' => \App\Models\Branch::with('users')->get(),
            'branch_comparison' => $this->getBranchComparison(),
            'regional_performance' => $this->getRegionalPerformance(),
            'user_distribution' => $this->getUserDistributionByBranch(),
            'growth_metrics' => $this->getGrowthMetrics(),
            'performance_trends' => $this->getPerformanceTrends(),
            'recent_activities' => ActivityLog::with('user')->latest()->limit(10)->get(),
        ];

        // Log activity
        if (Auth::check()) {
            Auth::user()->logActivity('dashboard', 'Accessed multi-branch analytics dashboard');
        }

        return view('dashboard.multi-branch-analytics', compact('stats'));
    }

    /**
     * Get user growth data
     */
    private function getUserGrowth()
    {
        return [
            'this_month' => User::whereMonth('created_at', now()->month)->count(),
            'last_month' => User::whereMonth('created_at', now()->subMonth()->month)->count(),
            'this_year' => User::whereYear('created_at', now()->year)->count(),
            'last_year' => User::whereYear('created_at', now()->subYear()->year)->count(),
        ];
    }

    /**
     * Get branch performance data
     */
    private function getBranchPerformance()
    {
        return \App\Models\Branch::withCount('users')->get()->map(function ($branch) {
            return [
                'name' => $branch->name,
                'users_count' => $branch->users_count,
                'active_users' => $branch->users()->where('is_active', true)->count(),
                'performance_score' => rand(70, 100), // Placeholder
            ];
        });
    }

    /**
     * Get role distribution
     */
    private function getRoleDistribution()
    {
        return \App\Models\Role::withCount('users')->get()->map(function ($role) {
            return [
                'name' => $role->name,
                'users_count' => $role->users_count,
                'percentage' => $role->users_count > 0 ? round(($role->users_count / User::count()) * 100, 2) : 0,
            ];
        });
    }

    /**
     * Get system health metrics
     */
    private function getSystemHealth()
    {
        return [
            'uptime' => '99.9%',
            'response_time' => '120ms',
            'error_rate' => '0.1%',
            'database_size' => '245 MB',
            'last_backup' => now()->subHours(2)->format('Y-m-d H:i:s'),
            'system_status' => 'Healthy',
        ];
    }

    /**
     * Get branch comparison data
     */
    private function getBranchComparison()
    {
        return \App\Models\Branch::with('users')->get()->map(function ($branch) {
            return [
                'name' => $branch->name,
                'total_users' => $branch->users->count(),
                'active_users' => $branch->users->where('is_active', true)->count(),
                'new_users_this_month' => $branch->users()->whereMonth('created_at', now()->month)->count(),
                'efficiency_score' => rand(75, 95),
            ];
        });
    }

    /**
     * Get regional performance data
     */
    private function getRegionalPerformance()
    {
        // Group branches by region (simulated)
        $regions = [
            'North' => ['Main Branch', 'North Branch'],
            'South' => ['South Branch', 'East Branch'],
            'Central' => ['Central Branch'],
        ];

        return collect($regions)->map(function ($branches, $region) {
            $branchIds = \App\Models\Branch::whereIn('name', $branches)->pluck('id');
            $users = User::whereIn('branch_id', $branchIds);
            
            return [
                'region' => $region,
                'total_users' => $users->count(),
                'active_users' => $users->where('is_active', true)->count(),
                'performance_score' => rand(80, 95),
            ];
        });
    }

    /**
     * Get user distribution by branch
     */
    private function getUserDistributionByBranch()
    {
        return \App\Models\Branch::with('users')->get()->map(function ($branch) {
            return [
                'branch_name' => $branch->name,
                'user_count' => $branch->users->count(),
                'percentage' => $branch->users->count() > 0 ? round(($branch->users->count() / User::count()) * 100, 2) : 0,
            ];
        });
    }

    /**
     * Get growth metrics
     */
    private function getGrowthMetrics()
    {
        return [
            'monthly_growth' => [
                'jan' => User::whereMonth('created_at', 1)->whereYear('created_at', now()->year)->count(),
                'feb' => User::whereMonth('created_at', 2)->whereYear('created_at', now()->year)->count(),
                'mar' => User::whereMonth('created_at', 3)->whereYear('created_at', now()->year)->count(),
                'apr' => User::whereMonth('created_at', 4)->whereYear('created_at', now()->year)->count(),
                'may' => User::whereMonth('created_at', 5)->whereYear('created_at', now()->year)->count(),
                'jun' => User::whereMonth('created_at', 6)->whereYear('created_at', now()->year)->count(),
            ],
            'total_growth_rate' => '15.3%',
        ];
    }

    /**
     * Get performance trends
     */
    private function getPerformanceTrends()
    {
        return [
            'user_activity' => [
                'monday' => 85,
                'tuesday' => 92,
                'wednesday' => 88,
                'thursday' => 95,
                'friday' => 90,
                'saturday' => 45,
                'sunday' => 30,
            ],
            'system_load' => [
                'monday' => 65,
                'tuesday' => 70,
                'wednesday' => 68,
                'thursday' => 75,
                'friday' => 72,
                'saturday' => 40,
                'sunday' => 25,
            ],
        ];
    }
    public function adminDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'branch_users' => User::where('branch_id', $user->branch_id)->count(),
            'recent_activities' => ActivityLog::with('user')
                ->whereHas('user', function($query) use ($user) {
                    $query->where('branch_id', $user->branch_id);
                })
                ->latest()
                ->limit(10)
                ->get(),
        ];

        $user->logActivity('dashboard_view', 'Viewed admin dashboard');

        return view('dashboard.admin', compact('stats'));
    }

    /**
     * Loan Officer Dashboard
     */
    public function loanOfficerDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'my_customers' => 0, // Will be implemented when Customer model is ready
            'pending_applications' => 0, // Will be implemented when Loan model is ready
            'recent_activities' => ActivityLog::where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get(),
        ];

        $user->logActivity('dashboard_view', 'Viewed loan officer dashboard');

        return view('dashboard.loan-officer', compact('stats'));
    }

    /**
     * Accountant Dashboard
     */
    public function accountantDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'recent_activities' => ActivityLog::where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get(),
        ];

        $user->logActivity('dashboard_view', 'Viewed accountant dashboard');

        return view('dashboard.accountant', compact('stats'));
    }

    /**
     * Collector Dashboard
     */
    public function collectorDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'recent_activities' => ActivityLog::where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get(),
        ];

        $user->logActivity('dashboard_view', 'Viewed collector dashboard');

        return view('dashboard.collector', compact('stats'));
    }

    /**
     * Auditor Dashboard
     */
    public function auditorDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'recent_activities' => ActivityLog::where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get(),
        ];

        $user->logActivity('dashboard_view', 'Viewed auditor dashboard');

        return view('dashboard.auditor', compact('stats'));
    }

    /**
     * Customer Dashboard
     */
    public function customerDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'recent_activities' => ActivityLog::where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get(),
        ];

        $user->logActivity('dashboard_view', 'Viewed customer dashboard');

        return view('dashboard.customer', compact('stats'));
    }

    /**
     * Guarantor Dashboard
     */
    public function guarantorDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'recent_activities' => ActivityLog::where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get(),
        ];

        $user->logActivity('dashboard_view', 'Viewed guarantor dashboard');

        return view('dashboard.guarantor', compact('stats'));
    }

    /**
     * IT Support Dashboard
     */
    public function itSupportDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'recent_activities' => ActivityLog::where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get(),
        ];

        $user->logActivity('dashboard_view', 'Viewed IT support dashboard');

        return view('dashboard.it-support', compact('stats'));
    }
}
