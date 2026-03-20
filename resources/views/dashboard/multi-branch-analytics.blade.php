@extends('layouts.app')

@section('title', 'Multi-branch Analytics')

@section('page-title', 'MULTI-BRANCH ANALYTICS')
@section('page-description', 'Comprehensive branch performance and regional analytics')

@section('content')
<div class="space-y-6">
    <!-- Analytics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Branches</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['branches']->count() }}</p>
                    <p class="text-xs text-green-600 mt-1">All operational</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['branches']->sum(function($branch) { return $branch->users->count(); }) }}</p>
                    <p class="text-xs text-blue-600 mt-1">{{ $stats['growth_metrics']['total_growth_rate'] }} growth</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Avg Efficiency</p>
                    <p class="text-2xl font-bold text-gray-900">{{ collect($stats['branch_comparison'])->avg('efficiency_score') }}%</p>
                    <p class="text-xs text-green-600 mt-1">Above target</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Regions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['regional_performance']->count() }}</p>
                    <p class="text-xs text-blue-600 mt-1">Active regions</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Branch Comparison Table -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Branch Performance Comparison</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New This Month</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Efficiency Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($stats['branch_comparison'] as $branch)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $branch['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $branch['total_users'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $branch['active_users'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $branch['new_users_this_month'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900">{{ $branch['efficiency_score'] }}%</span>
                                <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $branch['efficiency_score'] }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Regional Performance & User Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Regional Performance</h3>
            <div class="space-y-4">
                @foreach($stats['regional_performance'] as $region)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-sm font-medium text-gray-900">{{ $region['region'] }} Region</h4>
                        <span class="text-sm font-semibold text-green-600">{{ $region['performance_score'] }}%</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Total Users:</span>
                            <span class="font-medium text-gray-900 ml-1">{{ $region['total_users'] }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Active Users:</span>
                            <span class="font-medium text-gray-900 ml-1">{{ $region['active_users'] }}</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $region['performance_score'] }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Distribution by Branch</h3>
            <div class="space-y-3">
                @foreach($stats['user_distribution'] as $distribution)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span class="text-sm text-gray-700">{{ $distribution['branch_name'] }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-semibold text-gray-900">{{ $distribution['user_count'] }}</span>
                        <span class="text-xs text-gray-500">({{ $distribution['percentage'] }}%)</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Growth Metrics -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Growth Metrics</h3>
        <div class="mb-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Total Growth Rate</span>
                <span class="text-sm font-semibold text-green-600">{{ $stats['growth_metrics']['total_growth_rate'] }}</span>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($stats['growth_metrics']['monthly_growth'] as $month => $count)
            <div class="text-center">
                <div class="text-xs text-gray-600 mb-1">{{ ucfirst($month) }}</div>
                <div class="text-lg font-bold text-gray-900">{{ $count }}</div>
                <div class="text-xs text-gray-500">users</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Performance Trends -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Weekly Performance Trends</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-3">User Activity (%)</h4>
                <div class="space-y-2">
                    @foreach($stats['performance_trends']['user_activity'] as $day => $value)
                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-gray-600 w-16">{{ ucfirst($day) }}</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-4">
                            <div class="bg-blue-500 h-4 rounded-full" style="width: {{ $value }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-900 w-8">{{ $value }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-3">System Load (%)</h4>
                <div class="space-y-2">
                    @foreach($stats['performance_trends']['system_load'] as $day => $value)
                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-gray-600 w-16">{{ ucfirst($day) }}</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-4">
                            <div class="bg-green-500 h-4 rounded-full" style="width: {{ $value }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-900 w-8">{{ $value }}%</span>
                    </div>
                    @endforeach
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
