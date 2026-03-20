@extends('layouts.app')

@section('title', 'Loan Applications')

@section('page-title', 'LOAN APPLICATIONS')
@section('page-description', 'Review and approve loan applications')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Applications</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_applications']) }}</p>
                    <p class="text-xs text-blue-600 mt-1">In pipeline</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_review']) }}</p>
                    <p class="text-xs text-yellow-600 mt-1">Need attention</p>
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
                    <p class="text-sm text-gray-600 mb-1">Awaiting Disbursement</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['awaiting_disbursement']) }}</p>
                    <p class="text-xs text-green-600 mt-1">Ready to fund</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Avg Application</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['avg_application_amount'], 0) }}</p>
                    <p class="text-xs text-purple-600 mt-1">Per application</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-semibold text-gray-900">Application Queue</h3>
                <span class="text-sm text-gray-500">{{ $applications->total() }} applications</span>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                <form method="GET" action="{{ route('loans.applications') }}" class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search applications..." class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                    
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Filter
                    </button>
                </form>
                
                <a href="{{ route('loans.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    New Application
                </a>
            </div>
        </div>
    </div>

    <!-- Applications Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($applications as $loan)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-sm text-gray-600 font-medium">{{ substr($loan->customer->first_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $loan->customer->full_name }}</h4>
                            <p class="text-sm text-gray-500">Application #{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                    @switch($loan->status)
                        @case('pending')
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                Pending Review
                            </span>
                            @break
                        @case('approved')
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                Approved
                            </span>
                            @break
                    @endswitch
                </div>

                <div class="space-y-3 mb-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Loan Type:</span>
                            <span class="text-gray-900 ml-1">{{ ucfirst($loan->loan_type) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Amount:</span>
                            <span class="text-gray-900 ml-1">${{ number_format($loan->amount, 0) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Interest Rate:</span>
                            <span class="text-gray-900 ml-1">{{ $loan->interest_rate }}%</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Term:</span>
                            <span class="text-gray-900 ml-1">{{ $loan->term_months }} months</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Monthly Payment:</span>
                            <span class="text-gray-900 ml-1">${{ number_format($loan->monthly_payment, 0) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Applied:</span>
                            <span class="text-gray-900 ml-1">{{ $loan->application_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="text-sm">
                        <span class="text-gray-600">Purpose:</span>
                        <span class="text-gray-900 ml-1">{{ $loan->purpose }}</span>
                    </div>
                    
                    <div class="text-sm">
                        <span class="text-gray-600">Branch:</span>
                        <span class="text-gray-900 ml-1">{{ $loan->branch->name }} ({{ $loan->loanOfficer->name }})</span>
                    </div>
                </div>

                @if($loan->status === 'pending')
                <div class="border-t border-gray-200 pt-4">
                    <form method="POST" action="{{ route('loans.approve', $loan) }}" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Approved Amount</label>
                                <input type="number" name="approved_amount" value="{{ $loan->amount }}" max="{{ $loan->amount }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Interest Rate (%)</label>
                                <input type="number" name="approved_interest_rate" value="{{ $loan->interest_rate }}" step="0.01" max="50" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Approval Notes</label>
                            <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Add approval notes..."></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="submit" class="px-4 py-2 bg-green-500 border border-transparent rounded-lg font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                Approve Application
                            </button>
                            <form method="POST" action="{{ route('loans.reject', $loan) }}" class="inline">
                                @csrf
                                <input type="hidden" name="rejection_reason" value="Rejected during review process">
                                <button type="submit" class="px-4 py-2 bg-red-500 border border-transparent rounded-lg font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                    Reject Application
                                </button>
                            </form>
                            <a href="{{ route('loans.show', $loan) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                View Details
                            </a>
                        </div>
                    </form>
                </div>
                @else
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div>
                        @if($loan->approval_date)
                        <p class="text-sm text-gray-600">
                            Approved by: {{ $loan->approver->name ?? 'System' }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $loan->approval_date->format('M d, Y H:i') }}
                        </p>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('loans.disbursement') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Process Disbursement
                        </a>
                        <a href="{{ route('loans.show', $loan) }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                            View Details
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($applications->isEmpty())
    <div class="bg-white rounded-xl shadow-sm p-12 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No applications found</h3>
        <p class="text-gray-500 mb-6">No loan applications match your current filters.</p>
        <a href="{{ route('loans.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create Application
        </a>
    </div>
    @endif

    <!-- Pagination -->
    @if($applications->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $applications->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $applications->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $applications->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $applications->total() }}</span>
                        results
                    </p>
                </div>
                <div>
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
