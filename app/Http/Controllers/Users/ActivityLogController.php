<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'user.branch']);
        
        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', "%{$request->ip_address}%");
        }
        
        $logs = $query->latest()->paginate(50);
        
        $stats = [
            'total_logs' => ActivityLog::count(),
            'logs_today' => ActivityLog::whereDate('created_at', today())->count(),
            'logs_this_week' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'logs_this_month' => ActivityLog::whereMonth('created_at', now()->month)->count(),
            'unique_users' => ActivityLog::distinct('user_id')->count('user_id'),
            'most_common_action' => $this->getMostCommonAction(),
            'peak_activity_hour' => $this->getPeakActivityHour(),
        ];

        $users = User::whereHas('activityLogs')->get();
        $actions = ActivityLog::distinct('action')->pluck('action');

        return view('users.activity-logs.index', compact('logs', 'stats', 'users', 'actions'));
    }

    public function show(ActivityLog $log)
    {
        $log->load(['user', 'user.branch']);
        
        // Get additional context if available
        $additionalData = [];
        if ($log->additional_data) {
            $additionalData = json_decode($log->additional_data, true);
        }

        return view('users.activity-logs.show', compact('log', 'additionalData'));
    }

    public function destroy(ActivityLog $log)
    {
        $log->delete();

        // Log activity
        auth()->user()->logActivity('activity_log', "Deleted activity log ID: {$log->id}");

        return redirect()->route('activity-logs.index')
            ->with('success', 'Activity log deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with(['user', 'branch']);
        
        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->latest()->get();
        
        // Generate CSV
        $filename = 'activity_logs_' . date('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'ID',
                'User',
                'Branch',
                'Action',
                'Subject Type',
                'Subject ID',
                'Description',
                'IP Address',
                'User Agent',
                'Created At'
            ]);
            
            // Data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'N/A',
                    $log->branch ? $log->branch->name : 'N/A',
                    $log->action,
                    $log->subject_type,
                    $log->subject_id,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function cleanup(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);
        
        $cutoffDate = now()->subDays($validated['days']);
        $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();
        
        // Log the cleanup action
        auth()->user()->logActivity('system', "Cleaned up {$deletedCount} activity logs older than {$validated['days']} days");
        
        return redirect()->route('activity-logs.index')
            ->with('success', "Successfully deleted {$deletedCount} old activity logs.");
    }
    
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'log_ids' => 'required|array',
            'log_ids.*' => 'exists:activity_logs,id',
        ]);

        $deletedCount = ActivityLog::whereIn('id', $validated['log_ids'])->delete();

        // Log activity
        auth()->user()->logActivity('activity_log', "Bulk deleted {$deletedCount} activity logs");

        return redirect()->route('activity-logs.index')
            ->with('success', "{$deletedCount} activity logs deleted successfully.");
    }

    public function analytics()
    {
        $analytics = [
            'activity_trends' => $this->getActivityTrends(),
            'user_activity_breakdown' => $this->getUserActivityBreakdown(),
            'action_distribution' => $this->getActionDistribution(),
            'hourly_activity' => $this->getHourlyActivity(),
            'top_active_users' => $this->getTopActiveUsers(),
            'security_events' => $this->getSecurityEvents(),
        ];

        return view('users.activity-logs.analytics', compact('analytics'));
    }

    private function getMostCommonAction()
    {
        return ActivityLog::selectRaw('action, count(*) as count')
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->first()?->action ?? 'N/A';
    }

    private function getPeakActivityHour()
    {
        $peakHour = ActivityLog::selectRaw('HOUR(created_at) as hour, count(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->first();

        return $peakHour ? $peakHour->hour . ':00' : 'N/A';
    }

    private function getActivityTrends()
    {
        $dailyData = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $dailyData[] = [
                'date' => $day->format('Y-m-d'),
                'day_name' => $day->format('D'),
                'activities' => ActivityLog::whereDate('created_at', $day)->count(),
                'unique_users' => ActivityLog::whereDate('created_at', $day)->distinct('user_id')->count('user_id'),
            ];
        }
        return $dailyData;
    }

    private function getUserActivityBreakdown()
    {
        return User::whereHas('activity_logs')
            ->withCount('activity_logs')
            ->with(['activity_logs' => function($query) {
                $query->where('created_at', '>', now()->subDays(30));
            }])
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'total_activities' => $user->activity_logs_count,
                    'recent_activities' => $user->activity_logs->count(),
                    'branch' => $user->branch?->name ?? 'N/A',
                    'last_activity' => $user->activity_logs->max('created_at'),
                ];
            })
            ->sortByDesc('recent_activities')
            ->take(20)
            ->values();
    }

    private function getActionDistribution()
    {
        return ActivityLog::selectRaw('action, count(*) as count')
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'action' => $item->action,
                    'count' => $item->count,
                    'percentage' => round(($item->count / ActivityLog::count()) * 100, 2),
                ];
            });
    }

    private function getHourlyActivity()
    {
        $hourlyData = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $count = ActivityLog::whereRaw('HOUR(created_at) = ?', [$hour])->count();
            $hourlyData[] = [
                'hour' => $hour . ':00',
                'activities' => $count,
            ];
        }
        return $hourlyData;
    }

    private function getTopActiveUsers()
    {
        return User::whereHas('activity_logs')
            ->withCount(['activity_logs' => function($query) {
                $query->where('created_at', '>', now()->subDays(30));
            }])
            ->orderBy('activity_logs_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'activities' => $user->activity_logs_count,
                    'branch' => $user->branch?->name ?? 'N/A',
                    'roles' => $user->roles->pluck('name')->implode(', '),
                ];
            });
    }

    private function getSecurityEvents()
    {
        $securityActions = ['login', 'logout', 'failed_login', 'password_change', 'role_change', 'permission_change'];
        
        return [
            'total_security_events' => ActivityLog::whereIn('action', $securityActions)->count(),
            'failed_login_attempts' => ActivityLog::where('action', 'failed_login')->count(),
            'password_changes' => ActivityLog::where('action', 'password_change')->count(),
            'role_changes' => ActivityLog::where('action', 'role_change')->count(),
            'suspicious_activities' => ActivityLog::where('action', 'suspicious_activity')->count(),
            'recent_security_events' => ActivityLog::whereIn('action', $securityActions)
                ->with('user')
                ->latest()
                ->limit(10)
                ->get(),
        ];
    }
}
