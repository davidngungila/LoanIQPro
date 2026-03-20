<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::withCount('users');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        
        $roles = $query->orderBy('level')->paginate(15);
        
        $stats = [
            'total_roles' => Role::count(),
            'active_roles' => Role::where('is_active', true)->count(),
            'users_with_roles' => User::whereHas('roles')->count(),
            'users_without_roles' => User::whereDoesntHave('roles')->count(),
            'highest_level' => Role::max('level'),
            'lowest_level' => Role::min('level'),
        ];

        return view('users.roles.index', compact('roles', 'stats'));
    }

    public function create()
    {
        $permissions = $this->getAvailablePermissions();
        return view('users.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'slug' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string|max:1000',
            'level' => 'required|integer|min:1|max:100',
            'permissions' => 'required|array',
            'permissions.*' => 'string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['permissions'] = json_encode($validated['permissions']);

        $role = Role::create($validated);

        // Log activity
        auth()->user()->logActivity('role', "Created new role: {$role->name}");

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $role->load(['users' => function($query) {
            $query->with('branch')->latest();
        }]);

        $stats = [
            'total_users' => $role->users_count,
            'active_users' => $role->users->where('is_active', true)->count(),
            'inactive_users' => $role->users->where('is_active', false)->count(),
            'users_this_month' => $role->users()->whereMonth('created_at', now()->month)->count(),
            'permissions_count' => count(json_decode($role->permissions ?? '[]', true)),
        ];

        $permissions = json_decode($role->permissions ?? '[]', true);

        return view('users.roles.show', compact('role', 'stats', 'permissions'));
    }

    public function edit(Role $role)
    {
        $permissions = $this->getAvailablePermissions();
        $rolePermissions = json_decode($role->permissions ?? '[]', true);
        
        return view('users.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'description' => 'nullable|string|max:1000',
            'level' => 'required|integer|min:1|max:100',
            'permissions' => 'required|array',
            'permissions.*' => 'string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['permissions'] = json_encode($validated['permissions']);

        $role->update($validated);

        // Log activity
        auth()->user()->logActivity('role', "Updated role: {$role->name}");

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete role with assigned users. Please reassign users first.');
        }

        $roleName = $role->name;
        $role->delete();

        // Log activity
        auth()->user()->logActivity('role', "Deleted role: {$roleName}");

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    private function getAvailablePermissions()
    {
        return [
            'dashboard' => [
                'view_global_dashboard' => 'View Global Dashboard',
                'view_system_kpis' => 'View System KPIs',
                'view_analytics' => 'View Analytics',
            ],
            'users' => [
                'view_users' => 'View Users',
                'create_users' => 'Create Users',
                'edit_users' => 'Edit Users',
                'delete_users' => 'Delete Users',
                'manage_user_status' => 'Manage User Status',
            ],
            'roles' => [
                'view_roles' => 'View Roles',
                'create_roles' => 'Create Roles',
                'edit_roles' => 'Edit Roles',
                'delete_roles' => 'Delete Roles',
                'manage_permissions' => 'Manage Permissions',
            ],
            'organization' => [
                'view_branches' => 'View Branches',
                'manage_branches' => 'Manage Branches',
                'view_departments' => 'View Departments',
                'manage_departments' => 'Manage Departments',
                'view_staff' => 'View Staff',
                'manage_staff' => 'Manage Staff',
            ],
            'customers' => [
                'view_customers' => 'View Customers',
                'create_customers' => 'Create Customers',
                'edit_customers' => 'Edit Customers',
                'delete_customers' => 'Delete Customers',
                'manage_kyc' => 'Manage KYC',
            ],
            'loans' => [
                'view_loans' => 'View Loans',
                'create_loans' => 'Create Loans',
                'approve_loans' => 'Approve Loans',
                'reject_loans' => 'Reject Loans',
                'disburse_loans' => 'Disburse Loans',
                'manage_repayments' => 'Manage Repayments',
            ],
            'reports' => [
                'view_reports' => 'View Reports',
                'generate_reports' => 'Generate Reports',
                'export_reports' => 'Export Reports',
            ],
            'system' => [
                'view_activity_logs' => 'View Activity Logs',
                'manage_system_settings' => 'Manage System Settings',
                'view_audit_trail' => 'View Audit Trail',
            ],
        ];
    }

    public function assignUsers(Request $request, Role $role)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $role->users()->syncWithoutDetaching($validated['user_ids']);

        // Log activity
        auth()->user()->logActivity('role', "Assigned users to role: {$role->name}");

        return redirect()->route('roles.show', $role)
            ->with('success', 'Users assigned to role successfully.');
    }

    public function removeUser(Request $request, Role $role, User $user)
    {
        $role->users()->detach($user->id);

        // Log activity
        auth()->user()->logActivity('role', "Removed user {$user->name} from role: {$role->name}");

        return redirect()->route('roles.show', $role)
            ->with('success', 'User removed from role successfully.');
    }

    public function analytics()
    {
        $analytics = [
            'role_distribution' => $this->getRoleDistribution(),
            'permission_usage' => $this->getPermissionUsage(),
            'role_activity' => $this->getRoleActivity(),
            'user_role_changes' => $this->getUserRoleChanges(),
        ];

        return view('users.roles.analytics', compact('analytics'));
    }

    private function getRoleDistribution()
    {
        return Role::withCount('users')->get()->map(function ($role) {
            return [
                'name' => $role->name,
                'users_count' => $role->users_count,
                'percentage' => $role->users_count > 0 ? round(($role->users_count / User::whereHas('roles')->count()) * 100, 2) : 0,
                'level' => $role->level,
                'is_active' => $role->is_active,
            ];
        });
    }

    private function getPermissionUsage()
    {
        $allPermissions = [];
        $roles = Role::all();

        foreach ($roles as $role) {
            $permissions = json_decode($role->permissions ?? '[]', true);
            foreach ($permissions as $permission) {
                if (!isset($allPermissions[$permission])) {
                    $allPermissions[$permission] = 0;
                }
                $allPermissions[$permission]++;
            }
        }

        arsort($allPermissions);
        return array_slice($allPermissions, 0, 10, true);
    }

    private function getRoleActivity()
    {
        return Role::with('users')->get()->map(function ($role) {
            $recentActivities = 0;
            foreach ($role->users as $user) {
                $recentActivities += $user->activity_logs()->where('created_at', '>', now()->subDays(7))->count();
            }

            return [
                'name' => $role->name,
                'total_users' => $role->users->count(),
                'active_users' => $role->users->where('is_active', true)->count(),
                'recent_activities' => $recentActivities,
                'activity_score' => $role->users->count() > 0 ? round($recentActivities / $role->users->count(), 2) : 0,
            ];
        });
    }

    private function getUserRoleChanges()
    {
        // This would typically come from a role_assignment_history table
        // For now, we'll return simulated data
        return [
            'total_changes_this_month' => 15,
            'assignments_this_month' => 12,
            'removals_this_month' => 3,
            'most_changed_role' => 'Staff',
            'most_active_users' => 8,
        ];
    }
}
