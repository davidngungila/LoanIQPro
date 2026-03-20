@extends('layouts.app')

@section('title', 'System KPIs')

@section('page-title', 'SYSTEM KPIS')
@section('page-description', 'Key performance indicators and system metrics')

@section('content')
<div class="space-y-6">
    <!-- KPI Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    <p class="text-xs text-green-600 mt-1">+{{ $stats['user_growth']['this_month'] }} this month</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_users'] }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $stats['total_users'] > 0 ? round(($stats['active_users'] / $stats['total_users']) * 100, 1) : 0 }}% active rate</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Branches</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_branches'] }}</p>
                    <p class="text-xs text-blue-600 mt-1">{{ $stats['total_roles'] }} roles configured</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">System Status</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['system_health']['system_status'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['system_health']['uptime'] }} uptime</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- User Growth & Role Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Growth</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">This Month</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $stats['user_growth']['this_month'] }} users</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Last Month</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $stats['user_growth']['last_month'] }} users</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">This Year</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $stats['user_growth']['this_year'] }} users</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Last Year</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $stats['user_growth']['last_year'] }} users</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Distribution</h3>
            <div class="space-y-3">
                @foreach($stats['role_distribution'] as $role)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span class="text-sm text-gray-700">{{ $role['name'] }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-semibold text-gray-900">{{ $role['users_count'] }}</span>
                        <span class="text-xs text-gray-500">({{ $role['percentage'] }}%)</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Branch Performance & System Health -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Branch Performance</h3>
            <div class="space-y-3">
                @foreach($stats['branch_performance'] as $branch)
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-900">{{ $branch['name'] }}</span>
                        <span class="text-sm font-semibold text-green-600">{{ $branch['performance_score'] }}%</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600">
                        <span>{{ $branch['active_users'] }}/{{ $branch['users_count'] }} active</span>
                        <span>{{ $branch['users_count'] > 0 ? round(($branch['active_users'] / $branch['users_count']) * 100, 1) : 0 }}% active rate</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">System Health</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Uptime</span>
                    <span class="text-sm font-semibold text-green-600">{{ $stats['system_health']['uptime'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Response Time</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $stats['system_health']['response_time'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Error Rate</span>
                    <span class="text-sm font-semibold text-red-600">{{ $stats['system_health']['error_rate'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Database Size</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $stats['system_health']['database_size'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Last Backup</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $stats['system_health']['last_backup'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent System Activities</h3>
        <div class="space-y-3">
            @foreach($stats['recent_activities'] as $activity)
            <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-xs text-gray-600 font-medium">
                        {{ $activity->user ? substr($activity->user->name, 0, 1) : 'S' }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900">
                        <span class="font-medium">{{ $activity->user ? $activity->user->name : 'System' }}</span>
                        <span class="text-gray-600">{{ $activity->description }}</span>
                    </p>
                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
