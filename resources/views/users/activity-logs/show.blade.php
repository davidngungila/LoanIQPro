@extends('layouts.app')

@section('title', 'Activity Log Details')

@section('page-title', 'ACTIVITY LOG DETAILS')
@section('page-description', 'View detailed activity log information')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Activity Logs
        </a>
    </div>

    <!-- Log Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Basic Information</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Log ID</label>
                            <p class="text-sm text-gray-900">#{{ str_pad($log->id, 8, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $log->action }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Timestamp</label>
                        <p class="text-sm text-gray-900">{{ $log->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $log->description }}</p>
                    </div>
                </div>
                
                <!-- User Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">User Information</h3>
                    
                    @if($log->user)
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-lg text-gray-600 font-medium">{{ substr($log->user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $log->user->email }}</p>
                            <p class="text-sm text-gray-500">{{ $log->user->branch->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-lg text-gray-600 font-medium">S</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">System</p>
                            <p class="text-sm text-gray-500">Automated process</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                            <p class="text-sm text-gray-900">{{ $log->ip_address }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User Agent</label>
                            <p class="text-sm text-gray-900 truncate" title="{{ $log->user_agent }}">
                                {{ $log->user_agent ?: 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Subject Information -->
            @if($log->subject_type && $log->subject_id)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">Subject Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject Type</label>
                        <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $log->subject_type)) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject ID</label>
                        <p class="text-sm text-gray-900">#{{ $log->subject_id }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quick Actions</label>
                        <div class="flex space-x-2">
                            @if($log->subject_type === 'loan' && $loan = \App\Models\Loan::find($log->subject_id))
                                <a href="{{ route('loans.show', $loan) }}" class="text-blue-600 hover:text-blue-900 text-sm">View Loan</a>
                            @elseif($log->subject_type === 'customer' && $customer = \App\Models\Customer::find($log->subject_id))
                                <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900 text-sm">View Customer</a>
                            @elseif($log->subject_type === 'user' && $user = \App\Models\User::find($log->subject_id))
                                <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:text-blue-900 text-sm">View User</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Technical Details -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">Technical Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">User Agent</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg break-all">{{ $log->user_agent ?: 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Session ID</label>
                        <p class="text-sm text-gray-900">{{ session()->getId() }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        This log entry was recorded {{ $log->created_at->diffForHumans() }}
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            Back to List
                        </a>
                        
                        @if(auth()->user()->hasRole('super-admin'))
                        <form action="{{ route('activity-logs.destroy', $log) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this activity log? This action cannot be undone.')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-lg font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Log
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
