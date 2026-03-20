@extends('layouts.app')

@section('title', 'Loan Repayments')

@section('page-title', 'LOAN REPAYMENTS')
@section('page-description', 'Track loan payments and repayment schedules')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Loans</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_loans']) }}</p>
                    <p class="text-xs text-blue-600 mt-1">Currently active</p>
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
                    <p class="text-sm text-gray-600 mb-1">Overdue Loans</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['overdue_loans']) }}</p>
                    <p class="text-xs text-red-600 mt-1">Need attention</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Outstanding Balance</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_outstanding'], 0) }}</p>
                    <p class="text-xs text-purple-600 mt-1">Total owed</p>
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
                    <p class="text-sm text-gray-600 mb-1">Payments This Month</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['payments_this_month'], 0) }}</p>
                    <p class="text-xs text-green-600 mt-1">Collected</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-semibold text-gray-900">Repayment Tracking</h3>
                <span class="text-sm text-gray-500">{{ $loans->total() }} loans</span>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                <form method="GET" action="{{ route('loans.repayments') }}" class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search loans..." class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="current" {{ request('status') == 'current' ? 'selected' : '' }}>Current</option>
                        <option value="advanced" {{ request('status') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Filter
                    </button>
                </form>
                
                <div class="flex items-center space-x-3">
                    <button onclick="showScheduleModal()" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Payment Schedule
                    </button>
                    <button onclick="showOverdueModal()" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-lg font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Overdue Loans
                    </button>
                    <button onclick="showPaymentModal()" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-lg font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Record Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Repayment Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($loans as $loan)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-sm text-gray-600 font-medium">{{ substr($loan->customer->first_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $loan->customer->full_name }}</h4>
                            <p class="text-sm text-gray-500">Loan #{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                    @if($loan->days_past_due > 0)
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            {{ $loan->days_past_due }} days overdue
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Current
                        </span>
                    @endif
                </div>

                <div class="space-y-3 mb-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Loan Amount:</span>
                            <span class="text-gray-900 ml-1">${{ number_format($loan->amount, 0) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Outstanding:</span>
                            <span class="text-gray-900 ml-1 font-semibold">${{ number_format($loan->outstanding_balance, 0) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Monthly Payment:</span>
                            <span class="text-gray-900 ml-1">${{ number_format($loan->monthly_payment, 0) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Remaining Term:</span>
                            <span class="text-gray-900 ml-1">{{ $loan->remaining_term }} months</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Next Payment:</span>
                            <span class="text-gray-900 ml-1">{{ $loan->first_payment_date ? $loan->first_payment_date->copy()->addMonths($loan->term_months - $loan->remaining_term)->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Repaid:</span>
                            <span class="text-gray-900 ml-1">${{ number_format($loan->total_repaid, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                @if($loan->repayments->count() > 0)
                <div class="border-t border-gray-200 pt-4 mb-4">
                    <h5 class="text-sm font-medium text-gray-900 mb-2">Recent Payments</h5>
                    <div class="space-y-2">
                        @foreach($loan->repayments->take(3) as $payment)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">{{ $payment->payment_date->format('M d, Y') }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-gray-900 font-medium">${{ number_format($payment->amount, 0) }}</span>
                                @switch($payment->status)
                                    @case('paid')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Paid</span>
                                        @break
                                    @case('pending')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @break
                                    @case('failed')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Failed</span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex space-x-3">
                    <button onclick="recordPayment({{ $loan->id }})" class="px-4 py-2 bg-green-500 border border-transparent rounded-lg font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Record Payment
                    </button>
                    <button onclick="viewSchedule({{ $loan->id }})" class="px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        View Schedule
                    </button>
                    <a href="{{ route('loans.show', $loan) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($loans->isEmpty())
    <div class="bg-white rounded-xl shadow-sm p-12 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No active loans found</h3>
        <p class="text-gray-500 mb-6">No loans are currently active for repayment tracking.</p>
        <a href="{{ route('loans.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            View All Loans
        </a>
    </div>
    @endif

    <!-- Pagination -->
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

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Record Payment</h3>
            <form id="paymentForm" class="space-y-4">
                <input type="hidden" id="paymentLoanId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                    <input type="date" id="paymentDate" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Amount</label>
                    <input type="number" id="paymentAmount" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="0.00">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select id="paymentMethod" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">Select Method</option>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="check">Check</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="direct_debit">Direct Debit</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Reference</label>
                    <input type="text" id="paymentReference" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Enter reference">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="paymentNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Add payment notes..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hidePaymentModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-500 border border-transparent rounded-lg font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function recordPayment(loanId) {
    document.getElementById('paymentLoanId').value = loanId;
    document.getElementById('paymentDate').value = new Date().toISOString().split('T')[0];
    document.getElementById('paymentModal').classList.remove('hidden');
}

function hidePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}

function viewSchedule(loanId) {
    // Navigate to loan schedule page
    window.location.href = `/loans/${loanId}/schedule`;
}

function showScheduleModal() {
    alert('Payment Schedule feature coming soon!');
}

function showOverdueModal() {
    alert('Overdue Loans detailed view coming soon!');
}

function showPaymentModal() {
    alert('Bulk Payment Recording coming soon!');
}

document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const loanId = document.getElementById('paymentLoanId').value;
    const paymentData = {
        payment_date: document.getElementById('paymentDate').value,
        amount: document.getElementById('paymentAmount').value,
        payment_method: document.getElementById('paymentMethod').value,
        transaction_reference: document.getElementById('paymentReference').value,
        notes: document.getElementById('paymentNotes').value
    };
    
    // Here you would typically make an AJAX call to record the payment
    console.log('Recording payment for loan:', loanId, paymentData);
    alert('Payment recorded successfully!');
    hidePaymentModal();
    location.reload();
});
</script>
@endsection
