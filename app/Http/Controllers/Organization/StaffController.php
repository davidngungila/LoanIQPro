<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StaffController extends Controller
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
        
        $users = $query->latest()->paginate(15);
        
        $stats = [
            'total_staff' => User::count(),
            'active_staff' => User::where('is_active', true)->count(),
            'inactive_staff' => User::where('is_active', false)->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        $branches = Branch::where('is_active', true)->get();
        $roles = Role::all();

        return view('organization.staff.index', compact('users', 'stats', 'branches', 'roles'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        $roles = Role::all();
        return view('organization.staff.create', compact('branches', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'branch_id' => 'required|exists:branches,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'hire_date' => 'required|date',
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
                'hire_date' => $validated['hire_date'],
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
        auth()->user()->logActivity('staff', "Created new staff member: {$user->name}");

        return redirect()->route('organization.staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    public function edit(User $user)
    {
        $user->load(['roles', 'branch']);
        $branches = Branch::where('is_active', true)->get();
        $roles = Role::all();
        
        return view('organization.staff.edit', compact('user', 'branches', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'branch_id' => 'required|exists:branches,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'hire_date' => 'required|date',
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
        auth()->user()->logActivity('staff', "Updated staff member: {$user->name}");

        return redirect()->route('organization.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('organization.staff.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        // Log activity
        auth()->user()->logActivity('staff', "Deleted staff member: {$userName}");

        return redirect()->route('organization.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot change your own status.'], 403);
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        // Log activity
        auth()->user()->logActivity('staff', "{$status} staff member: {$user->name}");

        return response()->json([
            'success' => true,
            'message' => "Staff member {$status} successfully.",
            'is_active' => $user->is_active
        ]);
    }

    public function analytics(User $user)
    {
        $analytics = [
            'login_history' => $this->getLoginHistory($user),
            'activity_summary' => $this->getActivitySummary($user),
            'performance_metrics' => $this->getPerformanceMetrics($user),
            'role_history' => $this->getRoleHistory($user),
        ];

        return view('organization.staff.analytics', compact('user', 'analytics'));
    }

    private function getLoginHistory(User $user)
    {
        // This would typically come from a login_history table
        // For now, we'll simulate with recent activity logs
        return [
            'last_login' => $user->last_login_at,
            'total_logins' => $user->activity_logs()->count(),
            'login_frequency' => $this->calculateLoginFrequency($user),
            'most_active_day' => 'Monday', // Placeholder
            'avg_session_duration' => '2.5 hours', // Placeholder
        ];
    }

    private function getActivitySummary(User $user)
    {
        return [
            'total_activities' => $user->activity_logs()->count(),
            'activities_this_week' => $user->activity_logs()->where('created_at', '>', now()->subDays(7))->count(),
            'activities_this_month' => $user->activity_logs()->whereMonth('created_at', now()->month)->count(),
            'most_common_activity' => $user->activity_logs()
                ->selectRaw('action, count(*) as count')
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->first()?->action ?? 'N/A',
        ];
    }

    private function getPerformanceMetrics(User $user)
    {
        return [
            'productivity_score' => $this->calculateProductivityScore($user),
            'efficiency_rating' => $this->calculateEfficiencyRating($user),
            'task_completion_rate' => '85%', // Placeholder
            'quality_score' => '92%', // Placeholder
            'team_collaboration' => 'Good', // Placeholder
        ];
    }

    private function getRoleHistory(User $user)
    {
        return [
            'current_roles' => $user->roles->pluck('name'),
            'role_changes_count' => 0, // Placeholder - would track role changes
            'avg_role_duration' => '6 months', // Placeholder
            'role_compatibility' => 'High', // Placeholder
        ];
    }

    private function calculateLoginFrequency(User $user)
    {
        if (!$user->last_login_at) {
            return 'Never';
        }

        $daysSinceCreation = $user->created_at->diffInDays(now());
        $totalLogins = $user->activity_logs()->count(); // Simplified
        
        if ($daysSinceCreation === 0) {
            return 'N/A';
        }

        return round($totalLogins / $daysSinceCreation, 1) . ' per day';
    }

    private function calculateProductivityScore(User $user)
    {
        $activitiesThisMonth = $user->activity_logs()->whereMonth('created_at', now()->month)->count();
        $avgActivitiesPerUser = 30; // Placeholder average
        
        if ($avgActivitiesPerUser === 0) {
            return 0;
        }

        $score = min(($activitiesThisMonth / $avgActivitiesPerUser) * 100, 100);
        return round($score, 1);
    }

    private function calculateEfficiencyRating(User $user)
    {
        $productivityScore = $this->calculateProductivityScore($user);
        
        if ($productivityScore >= 90) return 'Excellent';
        if ($productivityScore >= 75) return 'Good';
        if ($productivityScore >= 60) return 'Average';
        if ($productivityScore >= 40) return 'Below Average';
        return 'Poor';
    }
}
