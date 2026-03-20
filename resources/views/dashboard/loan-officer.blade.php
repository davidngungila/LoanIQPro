@extends('layouts.app')

@section('title', 'Loan Officer Dashboard')

@section('page-title', 'Loan Management')
@section('page-description', 'Manage your loan applications and customers')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Your Activities</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($stats['recent_activities'] as $activity)
                        <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-medium text-blue-600">
                                    {{ $activity->user ? substr($activity->user->name, 0, 1) : 'S' }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">{{ $activity->user ? $activity->user->name : 'System' }}</span>
                                    <span class="text-gray-600">{{ $activity->description ?? $activity->action }}</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">No recent activities</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">My Customers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['my_customers'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pending Applications</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_applications'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
