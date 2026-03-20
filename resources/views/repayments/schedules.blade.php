@extends('layouts.app')

@section('title', 'Payment Schedules')

@section('page-title', 'PAYMENT SCHEDULES')
@section('page-description', 'View and manage loan payment schedules')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Scheduled Payments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['scheduled_payments']) }}</p>
                    <p class="text-xs text-blue-600 mt-1">Active schedules</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Next Payment Total</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['next_payment_total'], 0) }}</p>
                    <p class="text-xs text-green-600 mt-1">Due this month</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Payments This Week</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['payments_this_week']) }}</p>
                    <p class="text-xs text-purple-600 mt-1">Expected</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Upcoming Payments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['upcoming_payments']) }}</p>
                    <p class="text-xs text-yellow-600 mt-1">Next 30 days</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-semibold text-gray-900">Payment Schedules</h3>
                <span class="text-sm text-gray-500">{{ $loans->total() }} loans</span>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                <form method="GET" action="{{ route('repayments.schedules') }}" class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                    <select name="branch_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Filter
                    </button>
                </form>
                
                <a href="{{ route('repayments.tracking') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Back to Tracking
                </a>
            </div>
        </div>
    </div>

    <!-- Payment Schedule Calendar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Calendar</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- This Week -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 mb-3">This Week</h4>
                    <div class="space-y-2">
                        @for($i = 0; $i < 7; $i++)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ now()->startOfWeek()->addDays($i)->format('D, M d') }}</p>
                                <p class="text-xs text-gray-500">{{ $i + 1 }} payments expected</p>
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                ${{ number_format(($i + 1) * 2500, 0) }}
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
                
                <!-- Next Week -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 mb-3">Next Week</h4>
                    <div class="space-y-2">
                        @for($i = 7; $i < 14; $i++)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ now()->startOfWeek()->addDays($i)->format('D, M d') }}</p>
                                <p class="text-xs text-gray-500">{{ $i - 6 }} payments expected</p>
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                ${{ number_format(($i - 6) * 2500, 0) }}
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Schedule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($loans as $loan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-sm text-gray-500">{{ ucfirst($loan->loan_type) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-xs text-gray-600 font-medium">{{ substr($loan->customer->first_name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $loan->customer->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $loan->customer->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${{ number_format($loan->monthly_payment, 0) }}/month</div>
                            <div class="text-sm text-gray-500">{{ $loan->term_months }} payments</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($loan->first_payment_date)
                                    {{ $loan->first_payment_date->format('M d, Y') }}
                                @else
                                    Not scheduled
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">
                                @if($loan->first_payment_date)
                                    {{ $loan->first_payment_date->diffForHumans() }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($loan->status === 'active') bg-green-100 text-green-800
                                @elseif($loan->status === 'disbursed') bg-blue-100 text-blue-800
                                @elseif($loan->status === 'completed') bg-gray-100 text-gray-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form method="POST" action="{{ route('repayments.record-payment') }}" class="inline">
                                @csrf
                                <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                                <button type="submit" class="text-green-600 hover:text-green-900">Record Payment</button>
                            </form>
                            <a href="{{ route('loans.show', $loan) }}" class="text-gray-600 hover:text-gray-900 ml-2">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($loans->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $loans->links() }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $loans->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $loans->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $loans->total() }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        {{ $loans->links() }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
