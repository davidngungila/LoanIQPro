@extends('layouts.app')

@section('title', 'Customer Details')

@section('page-title', 'CUSTOMER DETAILS')
@section('page-description', 'View customer information and loan history')

@section('content')
<div class="space-y-6">
    <!-- Customer Overview -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                        <span class="text-xl text-gray-600 font-medium">{{ substr($customer->first_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $customer->full_name }}</h2>
                        <p class="text-gray-600">{{ $customer->email }}</p>
                        <p class="text-gray-600">{{ $customer->phone }}</p>
                        <div class="flex items-center mt-2 space-x-4">
                            @switch($customer->kyc_status)
                                @case('verified')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        KYC Verified
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        KYC Pending
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        KYC Rejected
                                    </span>
                                    @break
                            @endswitch
                            
                            @if($customer->is_active)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('loans.create') }}?customer_id={{ $customer->id }}" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-lg font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        New Loan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Loans</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_loans'] }}</p>
                    <p class="text-xs text-blue-600 mt-1">{{ $stats['active_loans'] }} active</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Portfolio</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_loan_amount'], 0) }}</p>
                    <p class="text-xs text-purple-600 mt-1">All loans</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Outstanding</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['outstanding_balance'], 0) }}</p>
                    <p class="text-xs text-yellow-600 mt-1">Current balance</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Customer Age</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['age'] }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $stats['account_age'] }} with us</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Information Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('personal')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm" data-tab="personal">
                    Personal Info
                </button>
                <button onclick="showTab('financial')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm" data-tab="financial">
                    Financial Info
                </button>
                <button onclick="showTab('loans')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm border-yellow-500 text-yellow-600" data-tab="loans">
                    Loan History
                </button>
                <button onclick="showTab('kyc')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm" data-tab="kyc">
                    KYC Status
                </button>
            </nav>
        </div>

        <!-- Personal Information Tab -->
        <div id="personal-tab" class="tab-content hidden p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Email:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->email }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Phone:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->phone }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Date of Birth:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->date_of_birth->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Gender:</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($customer->gender) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">National ID:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->national_id }}</dd>
                        </div>
                        @if($customer->passport_number)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Passport:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->passport_number }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Address:</dt>
                            <dd class="text-sm text-gray-900 text-right">{{ $customer->address }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">City:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->city }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">State:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->state }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Postal Code:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->postal_code }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Country:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->country }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            @if($customer->notes)
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Notes</h3>
                <p class="text-sm text-gray-600">{{ $customer->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Financial Information Tab -->
        <div id="financial-tab" class="tab-content hidden p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Employment Information</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Employment Status:</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $customer->employment_status)) }}</dd>
                        </div>
                        @if($customer->employer_name)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Employer:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->employer_name }}</dd>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Monthly Income:</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($customer->monthly_income, 0) }}</dd>
                        </div>
                    </dl>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Credit Information</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Credit Score:</dt>
                            <dd class="text-sm text-gray-900">
                                {{ $customer->credit_score }}
                                @if($customer->credit_score >= 750)
                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Excellent</span>
                                @elseif($customer->credit_score >= 700)
                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Good</span>
                                @elseif($customer->credit_score >= 650)
                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Fair</span>
                                @elseif($customer->credit_score > 0)
                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Poor</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Total Loan Amount:</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($stats['total_loan_amount'], 0) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Outstanding Balance:</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($stats['outstanding_balance'], 0) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Loan History Tab -->
        <div id="loans-tab" class="tab-content p-6">
            @if($customer->loans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customer->loans as $loan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ucfirst($loan->loan_type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($loan->amount, 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($loan->status)
                                    @case('pending')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @break
                                    @case('approved')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Approved</span>
                                        @break
                                    @case('disbursed')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Disbursed</span>
                                        @break
                                    @case('active')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                        @break
                                    @case('completed')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Completed</span>
                                        @break
                                    @case('defaulted')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Defaulted</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $loan->application_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('loans.show', $loan) }}" class="text-blue-600 hover:text-blue-900">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No loans found</h3>
                <p class="text-gray-500 mb-6">This customer hasn't taken any loans yet.</p>
                <a href="{{ route('loans.create') }}?customer_id={{ $customer->id }}" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-lg font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create First Loan
                </a>
            </div>
            @endif
        </div>

        <!-- KYC Status Tab -->
        <div id="kyc-tab" class="tab-content hidden p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">KYC Information</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">KYC Status:</dt>
                            <dd class="text-sm text-gray-900">
                                @switch($customer->kyc_status)
                                    @case('verified')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Verified</span>
                                        @break
                                    @case('pending')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @break
                                    @case('rejected')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @break
                                @endswitch
                            </dd>
                        </div>
                        @if($customer->kyc_verified_at)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Verified Date:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->kyc_verified_at->format('M d, Y H:i') }}</dd>
                        </div>
                        @endif
                        @if($customer->kycVerifier)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Verified By:</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->kycVerifier->name }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Verification Documents</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm text-gray-900">National ID</span>
                            </div>
                            <span class="text-sm text-green-600">Verified</span>
                        </div>
                        
                        @if($customer->passport_number)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm text-gray-900">Passport</span>
                            </div>
                            <span class="text-sm text-green-600">Verified</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($customer->kyc_status === 'pending')
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">KYC Pending</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>This customer's KYC verification is pending review. Please verify their identity documents to complete the process.</p>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('customers.kyc') }}" class="text-sm font-medium text-yellow-800 underline hover:text-yellow-900">
                                Review KYC →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active state from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-yellow-500', 'text-yellow-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active state to selected tab button
    const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
    activeButton.classList.remove('border-transparent', 'text-gray-500');
    activeButton.classList.add('border-yellow-500', 'text-yellow-600');
}
</script>
@endsection
