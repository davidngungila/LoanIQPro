<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::with(['users' => function($query) {
            $query->with('roles');
        }])->withCount('users')->get();
        
        $stats = [
            'total_branches' => $branches->count(),
            'active_branches' => $branches->where('is_active', true)->count(),
            'total_users' => $branches->sum('users_count'),
            'avg_users_per_branch' => $branches->count() > 0 ? round($branches->sum('users_count') / $branches->count(), 1) : 0,
        ];

        // Performance metrics
        foreach ($branches as $branch) {
            $branch->active_users_count = $branch->users->where('is_active', true)->count();
            $branch->user_growth_rate = $this->getUserGrowthRate($branch);
            $branch->performance_score = $this->calculatePerformanceScore($branch);
        }

        return view('organization.branches.index', compact('branches', 'stats'));
    }

    public function create()
    {
        return view('organization.branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:branches',
            'code' => 'required|string|max:10|unique:branches',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:branches',
            'manager_name' => 'required|string|max:255',
            'manager_email' => 'required|email|max:255',
            'manager_phone' => 'required|string|max:20',
            'opening_date' => 'required|date',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = auth()->id();

        $branch = Branch::create($validated);

        // Log activity
        auth()->user()->logActivity('branch', "Created new branch: {$branch->name}");

        return redirect()->route('organization.branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        $branch->load(['users' => function($query) {
            $query->with('roles');
        }]);
        
        $branch->stats = [
            'total_users' => $branch->users->count(),
            'active_users' => $branch->users->where('is_active', true)->count(),
            'user_growth_rate' => $this->getUserGrowthRate($branch),
            'performance_score' => $this->calculatePerformanceScore($branch),
        ];

        return view('organization.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('branches')->ignore($branch->id)],
            'code' => ['required', 'string', 'max:10', Rule::unique('branches')->ignore($branch->id)],
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => ['required', 'email', 'max:255', Rule::unique('branches')->ignore($branch->id)],
            'manager_name' => 'required|string|max:255',
            'manager_email' => 'required|email|max:255',
            'manager_phone' => 'required|string|max:20',
            'opening_date' => 'required|date',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['updated_by'] = auth()->id();

        $branch->update($validated);

        // Log activity
        auth()->user()->logActivity('branch', "Updated branch: {$branch->name}");

        return redirect()->route('organization.branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        // Check if branch has users
        if ($branch->users()->count() > 0) {
            return redirect()->route('organization.branches.index')
                ->with('error', 'Cannot delete branch with assigned users. Please reassign users first.');
        }

        $branchName = $branch->name;
        $branch->delete();

        // Log activity
        auth()->user()->logActivity('branch', "Deleted branch: {$branchName}");

        return redirect()->route('organization.branches.index')
            ->with('success', 'Branch deleted successfully.');
    }

    private function getUserGrowthRate(Branch $branch)
    {
        $thisMonthUsers = $branch->users()->whereMonth('created_at', now()->month)->count();
        $lastMonthUsers = $branch->users()->whereMonth('created_at', now()->subMonth()->month)->count();
        
        if ($lastMonthUsers == 0) {
            return $thisMonthUsers > 0 ? 100 : 0;
        }
        
        return round((($thisMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1);
    }

    private function calculatePerformanceScore(Branch $branch)
    {
        $totalUsers = $branch->users->count();
        $activeUsers = $branch->users->where('is_active', true)->count();
        
        if ($totalUsers == 0) {
            return 0;
        }
        
        $activeRate = ($activeUsers / $totalUsers) * 100;
        $growthRate = $this->getUserGrowthRate($branch);
        
        // Weighted score: 70% active rate, 30% growth rate
        $score = ($activeRate * 0.7) + (min($growthRate, 100) * 0.3);
        
        return round($score, 1);
    }

    public function analytics(Branch $branch)
    {
        $analytics = [
            'user_trends' => $this->getUserTrends($branch),
            'role_distribution' => $this->getRoleDistribution($branch),
            'activity_metrics' => $this->getActivityMetrics($branch),
            'performance_comparison' => $this->getPerformanceComparison($branch),
        ];

        return view('organization.branches.analytics', compact('branch', 'analytics'));
    }

    private function getUserTrends(Branch $branch)
    {
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'new_users' => $branch->users()->whereMonth('created_at', $month->month)->whereYear('created_at', $month->year)->count(),
                'active_users' => $branch->users()->whereMonth('last_login_at', $month->month)->whereYear('last_login_at', $month->year)->count(),
            ];
        }
        return $monthlyData;
    }

    private function getRoleDistribution(Branch $branch)
    {
        return $branch->users()->with('roles')->get()->flatMap(function ($user) {
            return $user->roles->map(function ($role) {
                return [
                    'role_name' => $role->name,
                    'role_slug' => $role->slug,
                ];
            });
        })->groupBy('role_name')->map(function ($group) {
            return [
                'count' => $group->count(),
                'percentage' => round(($group->count() / $branch->users->count()) * 100, 1),
            ];
        });
    }

    private function getActivityMetrics(Branch $branch)
    {
        return [
            'total_logins' => $branch->users()->whereNotNull('last_login_at')->count(),
            'recent_logins' => $branch->users()->where('last_login_at', '>', now()->subDays(7))->count(),
            'inactive_users' => $branch->users()->where('last_login_at', '<', now()->subDays(30))->count(),
            'avg_login_frequency' => $this->calculateAvgLoginFrequency($branch),
        ];
    }

    private function getPerformanceComparison(Branch $branch)
    {
        $allBranches = Branch::withCount('users')->get();
        $avgUsersPerBranch = $allBranches->avg('users_count');
        $avgActiveRate = $allBranches->map(function ($b) {
            return $b->users()->where('is_active', true)->count() / max($b->users_count, 1) * 100;
        })->avg();

        return [
            'users_vs_average' => [
                'branch_users' => $branch->users->count(),
                'average_users' => round($avgUsersPerBranch, 1),
                'performance' => $branch->users->count() >= $avgUsersPerBranch ? 'Above Average' : 'Below Average',
            ],
            'active_rate_vs_average' => [
                'branch_rate' => $branch->users->where('is_active', true)->count() / max($branch->users->count(), 1) * 100,
                'average_rate' => round($avgActiveRate, 1),
                'performance' => $branch->users->where('is_active', true)->count() / max($branch->users->count(), 1) * 100 >= $avgActiveRate ? 'Above Average' : 'Below Average',
            ],
        ];
    }

    private function calculateAvgLoginFrequency(Branch $branch)
    {
        // This is a simplified calculation - in a real system, you'd track login history
        $activeUsers = $branch->users()->whereNotNull('last_login_at')->count();
        $totalUsers = $branch->users->count();
        
        return $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0;
    }
}
