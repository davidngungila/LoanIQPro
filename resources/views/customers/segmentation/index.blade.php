@extends('layouts.app')

@section('title', 'Customer Segmentation')

@section('page-title', 'CUSTOMER SEGMENTATION')
@section('page-description', 'Analyze customer segments and demographics')

@section('content')
<div class="space-y-6">
    <!-- Income Segmentation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Income Segmentation</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_income']['low_income']) }}</h4>
                    <p class="text-sm text-gray-600">Low Income</p>
                    <p class="text-xs text-gray-500 mt-1">&lt; $1,000/month</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_income']['middle_income']) }}</h4>
                    <p class="text-sm text-gray-600">Middle Income</p>
                    <p class="text-xs text-gray-500 mt-1">$1,000 - $5,000/month</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_income']['high_income']) }}</h4>
                    <p class="text-sm text-gray-600">High Income</p>
                    <p class="text-xs text-gray-500 mt-1">&gt; $5,000/month</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">${{ number_format($segments['by_income']['avg_income'], 0) }}</h4>
                    <p class="text-sm text-gray-600">Average Income</p>
                    <p class="text-xs text-gray-500 mt-1">Per month</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Credit Score Segmentation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Credit Score Segmentation</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-lg font-bold text-green-600">A+</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_credit_score']['excellent']) }}</h4>
                    <p class="text-sm text-gray-600">Excellent</p>
                    <p class="text-xs text-gray-500 mt-1">750+</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-lg font-bold text-blue-600">A</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_credit_score']['good']) }}</h4>
                    <p class="text-sm text-gray-600">Good</p>
                    <p class="text-xs text-gray-500 mt-1">700-749</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-lg font-bold text-yellow-600">B</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_credit_score']['fair']) }}</h4>
                    <p class="text-sm text-gray-600">Fair</p>
                    <p class="text-xs text-gray-500 mt-1">650-699</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-lg font-bold text-red-600">C</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_credit_score']['poor']) }}</h4>
                    <p class="text-sm text-gray-600">Poor</p>
                    <p class="text-xs text-gray-500 mt-1">&lt; 650</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ round($segments['by_credit_score']['avg_score']) }}</h4>
                    <p class="text-sm text-gray-600">Avg Score</p>
                    <p class="text-xs text-gray-500 mt-1">Overall</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Employment Segmentation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Employment Segmentation</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach($segments['by_employment'] as $status => $count)
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($count) }}</h4>
                    <p class="text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $status)) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Loan History Segmentation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan History Segmentation</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_loan_history']['no_loans']) }}</h4>
                    <p class="text-sm text-gray-600">No Loans</p>
                    <p class="text-xs text-gray-500 mt-1">First time customers</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_loan_history']['first_time']) }}</h4>
                    <p class="text-sm text-gray-600">First Time</p>
                    <p class="text-xs text-gray-500 mt-1">Active first loan</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_loan_history']['repeat_customers']) }}</h4>
                    <p class="text-sm text-gray-600">Repeat</p>
                    <p class="text-xs text-gray-500 mt-1">Multiple loans</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Age Segmentation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Age Segmentation</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-lg font-bold text-pink-600">18-25</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_age']['18-25']) }}</h4>
                    <p class="text-sm text-gray-600">Young Adults</p>
                    <p class="text-xs text-gray-500 mt-1">Students & early career</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-lg font-bold text-blue-600">26-35</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_age']['26-35']) }}</h4>
                    <p class="text-sm text-gray-600">Young Professionals</p>
                    <p class="text-xs text-gray-500 mt-1">Career building</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-lg font-bold text-green-600">36-45</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_age']['36-45']) }}</h4>
                    <p class="text-sm text-gray-600">Established</p>
                    <p class="text-xs text-gray-500 mt-1">Peak earning years</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-lg font-bold text-yellow-600">46-55</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_age']['46-55']) }}</h4>
                    <p class="text-sm text-gray-600">Mid-Career</p>
                    <p class="text-xs text-gray-500 mt-1">Senior positions</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-lg font-bold text-purple-600">55+</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ number_format($segments['by_age']['55+']) }}</h4>
                    <p class="text-sm text-gray-600">Seniors</p>
                    <p class="text-xs text-gray-500 mt-1">Retirement planning</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Insights and Recommendations -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Key Insights & Recommendations</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-3">Customer Profile Analysis</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Majority of customers fall in the middle-income bracket
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Strong employment status indicates stable customer base
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-yellow-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Opportunity to increase loan products for no-loan segment
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Age distribution shows diverse customer demographics
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-3">Marketing Recommendations</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Target young professionals (26-35) with starter loan products
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Develop premium products for high-income segment
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Focus KYC outreach on pending verification customers
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Create loyalty programs for repeat customers
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
