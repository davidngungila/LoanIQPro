<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'branch']);
        
        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        
        if ($request->filled('role_id')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('role_id', $request->role_id);
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $users = $query->latest()->paginate(20);
        
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'new_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'users_with_branches' => User::whereNotNull('branch_id')->count(),
            'users_without_branches' => User::whereNull('branch_id')->count(),
        ];

        $branches = Branch::where('is_active', true)->get();
        $roles = Role::all();

        return view('users.users.index', compact('users', 'stats', 'branches', 'roles'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        $roles = Role::all();
        return view('users.users.create', compact('branches', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'branch_id' => 'nullable|exists:branches,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        $user = DB::transaction(function() use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'branch_id' => $validated['branch_id'],
                'password' => $validated['password'],
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'hire_date' => $validated['hire_date'] ?? null,
                'salary' => $validated['salary'] ?? null,
                'department' => $validated['department'] ?? null,
                'position' => $validated['position'] ?? null,
                'is_active' => $validated['is_active'],
                'email_verified_at' => $validated['email_verified_at'],
            ]);

            $user->roles()->attach($validated['roles']);

            return $user;
        });

        // Log activity
        auth()->user()->logActivity('user', "Created new user: {$user->name}");

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'branch', 'activityLogs' => function($query) {
            $query->latest()->limit(50);
        }]);

        $stats = [
            'total_activities' => $user->activityLogs()->count(),
            'activities_this_week' => $user->activityLogs()->where('created_at', '>', now()->subDays(7))->count(),
            'activities_this_month' => $user->activityLogs()->whereMonth('created_at', now()->month)->count(),
            'last_login' => $user->last_login_at,
            'account_age' => $user->created_at->diffForHumans(),
            'login_frequency' => $this->calculateLoginFrequency($user),
        ];

        return view('users.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        $user->load(['roles', 'branch']);
        $branches = Branch::where('is_active', true)->get();
        $roles = Role::all();
        
        return view('users.users.edit', compact('user', 'branches', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'branch_id' => 'nullable|exists:branches,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        DB::transaction(function() use ($user, $validated) {
            $user->update($validated);
            $user->roles()->sync($validated['roles']);
        });

        // Log activity
        auth()->user()->logActivity('user', "Updated user: {$user->name}");

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        // Log activity
        auth()->user()->logActivity('user', "Deleted user: {$userName}");

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot change your own status.'], 403);
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        // Log activity
        auth()->user()->logActivity('user', "{$status} user: {$user->name}");

        return response()->json([
            'success' => true,
            'message' => "User {$status} successfully.",
            'is_active' => $user->is_active
        ]);
    }

    private function calculateLoginFrequency(User $user)
    {
        if (!$user->last_login_at) {
            return 'Never';
        }

        $daysSinceCreation = $user->created_at->diffInDays(now());
        $totalLogins = $user->activityLogs()->count();
        
        if ($daysSinceCreation === 0) {
            return 'N/A';
        }

        return round($totalLogins / $daysSinceCreation, 1) . ' per day';
    }

    public function analytics()
    {
        $analytics = [
            'user_growth' => $this->getUserGrowthData(),
            'role_distribution' => $this->getRoleDistribution(),
            'branch_distribution' => $this->getBranchDistribution(),
            'activity_trends' => $this->getActivityTrends(),
            'user_status_breakdown' => $this->getUserStatusBreakdown(),
        ];

        return view('users.users.analytics', compact('analytics'));
    }

    private function getUserGrowthData()
    {
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'new_users' => User::whereMonth('created_at', $month->month)->whereYear('created_at', $month->year)->count(),
                'active_users' => User::whereMonth('last_login_at', $month->month)->whereYear('last_login_at', $month->year)->count(),
            ];
        }
        return $monthlyData;
    }

    private function getRoleDistribution()
    {
        return Role::withCount('users')->get()->map(function ($role) {
            return [
                'name' => $role->name,
                'users_count' => $role->users_count,
                'percentage' => $role->users_count > 0 ? round(($role->users_count / User::count()) * 100, 2) : 0,
            ];
        });
    }

    private function getBranchDistribution()
    {
        return Branch::withCount('users')->get()->map(function ($branch) {
            return [
                'name' => $branch->name,
                'users_count' => $branch->users_count,
                'active_users' => $branch->users()->where('is_active', true)->count(),
                'percentage' => $branch->users_count > 0 ? round(($branch->users_count / User::count()) * 100, 2) : 0,
            ];
        });
    }

    private function getActivityTrends()
    {
        return [
            'daily_activity' => $this->getDailyActivity(),
            'weekly_activity' => $this->getWeeklyActivity(),
            'monthly_activity' => $this->getMonthlyActivity(),
        ];
    }

    private function getDailyActivity()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $data[] = [
                'day' => $day->format('D'),
                'activities' => \App\Models\ActivityLog::whereDate('created_at', $day)->count(),
            ];
        }
        return $data;
    }

    private function getWeeklyActivity()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $week = now()->subWeeks($i);
            $data[] = [
                'week' => $week->format('W'),
                'activities' => \App\Models\ActivityLog::whereBetween('created_at', [$week->startOfWeek(), $week->endOfWeek()])->count(),
            ];
        }
        return $data;
    }

    private function getMonthlyActivity()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $data[] = [
                'month' => $month->format('M'),
                'activities' => \App\Models\ActivityLog::whereMonth('created_at', $month->month)->whereYear('created_at', $month->year)->count(),
            ];
        }
        return $data;
    }

    private function getUserStatusBreakdown()
    {
        return [
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'never_logged_in' => User::whereNull('last_login_at')->count(),
            'recently_active' => User::where('last_login_at', '>', now()->subDays(7))->count(),
        ];
    }
}
