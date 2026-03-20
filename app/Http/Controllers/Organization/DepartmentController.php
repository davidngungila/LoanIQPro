<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::with(['users' => function($query) {
            $query->with('roles')->with('branch');
        }])->withCount('users')->get();
        
        $stats = [
            'total_departments' => $departments->count(),
            'active_departments' => $departments->where('is_active', true)->count(),
            'total_users' => $departments->sum('users_count'),
            'avg_users_per_department' => $departments->count() > 0 ? round($departments->sum('users_count') / $departments->count(), 1) : 0,
        ];

        // Additional metrics
        foreach ($departments as $department) {
            $department->active_users_count = $department->users->where('is_active', true)->count();
            $department->branches_count = $department->users->pluck('branch_id')->unique()->count();
            $department->roles_diversity = $department->users->flatMap(function($user) {
                return $user->roles->pluck('name');
            })->unique()->count();
        }

        return view('organization.departments.index', compact('departments', 'stats'));
    }

    public function create()
    {
        $branches = \App\Models\Branch::where('is_active', true)->get();
        return view('organization.departments.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'code' => 'required|string|max:10|unique:departments',
            'description' => 'nullable|string|max:1000',
            'head_of_department' => 'nullable|string|max:255',
            'head_email' => 'nullable|email|max:255',
            'head_phone' => 'nullable|string|max:20',
            'budget' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = auth()->id();

        $department = Department::create($validated);

        // Log activity
        auth()->user()->logActivity('department', "Created new department: {$department->name}");

        return redirect()->route('organization.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        $department->load(['users' => function($query) {
            $query->with('roles')->with('branch');
        }]);
        
        $department->stats = [
            'total_users' => $department->users->count(),
            'active_users' => $department->users->where('is_active', true)->count(),
            'branches_count' => $department->users->pluck('branch_id')->unique()->count(),
            'roles_diversity' => $department->users->flatMap(function($user) {
                return $user->roles->pluck('name');
            })->unique()->count(),
        ];

        $branches = \App\Models\Branch::where('is_active', true)->get();

        return view('organization.departments.edit', compact('department', 'branches'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->ignore($department->id)],
            'code' => ['required', 'string', 'max:10', Rule::unique('departments')->ignore($department->id)],
            'description' => 'nullable|string|max:1000',
            'head_of_department' => 'nullable|string|max:255',
            'head_email' => 'nullable|email|max:255',
            'head_phone' => 'nullable|string|max:20',
            'budget' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['updated_by'] = auth()->id();

        $department->update($validated);

        // Log activity
        auth()->user()->logActivity('department', "Updated department: {$department->name}");

        return redirect()->route('organization.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        // Check if department has users
        if ($department->users()->count() > 0) {
            return redirect()->route('organization.departments.index')
                ->with('error', 'Cannot delete department with assigned users. Please reassign users first.');
        }

        $departmentName = $department->name;
        $department->delete();

        // Log activity
        auth()->user()->logActivity('department', "Deleted department: {$departmentName}");

        return redirect()->route('organization.departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    public function analytics(Department $department)
    {
        $analytics = [
            'user_trends' => $this->getUserTrends($department),
            'role_distribution' => $this->getRoleDistribution($department),
            'branch_distribution' => $this->getBranchDistribution($department),
            'activity_metrics' => $this->getActivityMetrics($department),
            'budget_utilization' => $this->getBudgetUtilization($department),
        ];

        return view('organization.departments.analytics', compact('department', 'analytics'));
    }

    private function getUserTrends(Department $department)
    {
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'new_users' => $department->users()->whereMonth('created_at', $month->month)->whereYear('created_at', $month->year)->count(),
                'active_users' => $department->users()->whereMonth('last_login_at', $month->month)->whereYear('last_login_at', $month->year)->count(),
            ];
        }
        return $monthlyData;
    }

    private function getRoleDistribution(Department $department)
    {
        return $department->users()->with('roles')->get()->flatMap(function ($user) {
            return $user->roles->map(function ($role) {
                return [
                    'role_name' => $role->name,
                    'role_slug' => $role->slug,
                ];
            });
        })->groupBy('role_name')->map(function ($group) use ($department) {
            return [
                'count' => $group->count(),
                'percentage' => round(($group->count() / $department->users->count()) * 100, 1),
            ];
        });
    }

    private function getBranchDistribution(Department $department)
    {
        return $department->users()->with('branch')->get()
            ->groupBy('branch.name')
            ->map(function ($users, $branchName) use ($department) {
                return [
                    'branch_name' => $branchName,
                    'user_count' => $users->count(),
                    'percentage' => round(($users->count() / $department->users->count()) * 100, 1),
                    'active_users' => $users->where('is_active', true)->count(),
                ];
            });
    }

    private function getActivityMetrics(Department $department)
    {
        return [
            'total_logins' => $department->users()->whereNotNull('last_login_at')->count(),
            'recent_logins' => $department->users()->where('last_login_at', '>', now()->subDays(7))->count(),
            'inactive_users' => $department->users()->where('last_login_at', '<', now()->subDays(30))->count(),
            'productivity_score' => $this->calculateProductivityScore($department),
        ];
    }

    private function getBudgetUtilization(Department $department)
    {
        $budget = $department->budget ?? 0;
        $estimatedCost = $department->users->count() * 50000; // Estimated cost per employee
        
        return [
            'allocated_budget' => $budget,
            'estimated_cost' => $estimatedCost,
            'utilization_rate' => $budget > 0 ? round(($estimatedCost / $budget) * 100, 1) : 0,
            'efficiency' => $budget > 0 ? ($estimatedCost <= $budget ? 'Within Budget' : 'Over Budget') : 'No Budget Set',
        ];
    }

    private function calculateProductivityScore(Department $department)
    {
        $totalUsers = $department->users->count();
        $activeUsers = $department->users()->where('is_active', true)->count();
        $recentLogins = $department->users()->where('last_login_at', '>', now()->subDays(7))->count();
        
        if ($totalUsers == 0) {
            return 0;
        }
        
        $activeRate = ($activeUsers / $totalUsers) * 100;
        $engagementRate = ($recentLogins / $totalUsers) * 100;
        
        // Weighted score: 50% active rate, 50% engagement rate
        $score = ($activeRate * 0.5) + ($engagementRate * 0.5);
        
        return round($score, 1);
    }
}
