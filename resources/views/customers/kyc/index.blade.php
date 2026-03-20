@extends('layouts.app')

@section('title', 'KYC Verification')

@section('page-title', 'KYC VERIFICATION')
@section('page-description', 'Manage customer identity verification')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_pending']) }}</p>
                    <p class="text-xs text-yellow-600 mt-1">Awaiting verification</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Verified</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_verified']) }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $stats['verification_rate'] }}% rate</p>
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
                    <p class="text-sm text-gray-600 mb-1">Rejected</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_rejected']) }}</p>
                    <p class="text-xs text-red-600 mt-1">Needs review</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Processed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_verified'] + $stats['total_rejected']) }}</p>
                    <p class="text-xs text-blue-600 mt-1">Completed reviews</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-semibold text-gray-900">KYC Verification Queue</h3>
                <span class="text-sm text-gray-500">{{ $customers->total() }} customers</span>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                <form method="GET" action="{{ route('customers.kyc') }}" class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customers..." class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Filter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- KYC Verification Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($customers as $customer)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-sm text-gray-600 font-medium">{{ substr($customer->first_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $customer->full_name }}</h4>
                            <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                        </div>
                    </div>
                    @switch($customer->kyc_status)
                        @case('pending')
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                            @break
                        @case('verified')
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                Verified
                            </span>
                            @break
                        @case('rejected')
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                Rejected
                            </span>
                            @break
                    @endswitch
                </div>

                <div class="space-y-3 mb-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Phone:</span>
                            <span class="text-gray-900 ml-1">{{ $customer->phone }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Age:</span>
                            <span class="text-gray-900 ml-1">{{ $customer->age }} years</span>
                        </div>
                        <div>
                            <span class="text-gray-600">National ID:</span>
                            <span class="text-gray-900 ml-1">{{ $customer->national_id }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Employment:</span>
                            <span class="text-gray-900 ml-1">{{ $customer->employment_status }}</span>
                        </div>
                    </div>
                    
                    <div class="text-sm">
                        <span class="text-gray-600">Address:</span>
                        <span class="text-gray-900 ml-1">{{ $customer->address }}, {{ $customer->city }}</span>
                    </div>
                    
                    @if($customer->monthly_income > 0)
                    <div class="text-sm">
                        <span class="text-gray-600">Income:</span>
                        <span class="text-gray-900 ml-1">${{ number_format($customer->monthly_income, 0) }}/month</span>
                    </div>
                    @endif
                </div>

                @if($customer->kyc_status === 'pending')
                <form method="POST" action="{{ route('customers.verify-kyc', $customer) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Verification Decision</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <option value="">Select Decision</option>
                            <option value="verified">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Add verification notes..."></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                            Submit Decision
                        </button>
                        <a href="{{ route('customers.show', $customer) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            View Details
                        </a>
                    </div>
                </form>
                @else
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div>
                        @if($customer->kyc_verified_at)
                        <p class="text-sm text-gray-600">
                            Verified by: {{ $customer->kycVerifier->name ?? 'System' }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $customer->kyc_verified_at->format('M d, Y H:i') }}
                        </p>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View Details
                        </a>
                        @if($customer->kyc_status === 'pending')
                            <form method="POST" action="{{ route('customers.verify-kyc', $customer) }}" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                    Review Again
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($customers->isEmpty())
    <div class="bg-white rounded-xl shadow-sm p-12 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No KYC requests found</h3>
        <p class="text-gray-500">No KYC verification requests match your current filters.</p>
    </div>
    @endif

    <!-- Pagination -->
    @if($customers->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $customers->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $customers->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $customers->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $customers->total() }}</span>
                        results
                    </p>
                </div>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
