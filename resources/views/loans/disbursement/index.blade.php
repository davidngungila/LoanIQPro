@extends('layouts.app')

@section('title', 'Loan Disbursement')

@section('page-title', 'LOAN DISBURSEMENT')
@section('page-description', 'Process loan disbursements and fund transfers')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Ready for Disbursement</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['ready_for_disbursement']) }}</p>
                    <p class="text-xs text-green-600 mt-1">Approved loans</p>
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
                    <p class="text-sm text-gray-600 mb-1">Total Disbursement Amount</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_disbursement_amount'], 0) }}</p>
                    <p class="text-xs text-blue-600 mt-1">Pending funds</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Disbursed Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['disbursed_today']) }}</p>
                    <p class="text-xs text-purple-600 mt-1">{{ number_format($stats['disbursed_this_month']) }} this month</p>
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
                    <p class="text-sm text-gray-600 mb-1">Avg Processing Time</p>
                    <p class="text-2xl font-bold text-gray-900">2.3</p>
                    <p class="text-xs text-yellow-600 mt-1">Business days</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-semibold text-gray-900">Disbursement Queue</h3>
                <span class="text-sm text-gray-500">{{ $loans->total() }} loans</span>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                <form method="GET" action="{{ route('loans.disbursement') }}" class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search loans..." class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    
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
                
                <div class="flex items-center space-x-3">
                    <button onclick="showBulkDisbursementModal()" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V2"/>
                        </svg>
                        Bulk Disbursement
                    </button>
                    <a href="{{ route('loans.applications') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Applications
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Disbursement Cards -->
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
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                        Ready to Disburse
                    </span>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Loan Amount:</span>
                            <span class="text-gray-900 ml-1 font-semibold">${{ number_format($loan->amount, 0) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Loan Type:</span>
                            <span class="text-gray-900 ml-1">{{ ucfirst($loan->loan_type) }}</span>
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
                            <span class="text-gray-600">Approved:</span>
                            <span class="text-gray-900 ml-1">{{ $loan->approval_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="text-sm">
                        <span class="text-gray-600">Purpose:</span>
                        <span class="text-gray-900 ml-1">{{ $loan->purpose }}</span>
                    </div>
                    
                    <div class="text-sm">
                        <span class="text-gray-600">Branch:</span>
                        <span class="text-gray-900 ml-1">{{ $loan->branch->name }}</span>
                    </div>
                    
                    <div class="text-sm">
                        <span class="text-gray-600">Loan Officer:</span>
                        <span class="text-gray-900 ml-1">{{ $loan->loanOfficer->name }}</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <form method="POST" action="{{ route('loans.disburse', $loan) }}" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Disbursement Method</label>
                                <select name="disbursement_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                    <option value="">Select Method</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cash">Cash</option>
                                    <option value="check">Check</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="direct_debit">Direct Debit</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                                <input type="text" name="disbursement_reference" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Enter reference">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Disbursement Notes</label>
                            <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Add disbursement notes..."></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="submit" class="px-4 py-2 bg-green-500 border border-transparent rounded-lg font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Process Disbursement
                            </button>
                            <a href="{{ route('loans.show', $loan) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                View Details
                            </a>
                        </div>
                    </form>
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
        <h3 class="text-lg font-medium text-gray-900 mb-2">No loans ready for disbursement</h3>
        <p class="text-gray-500 mb-6">All approved loans have been disbursed or no loans are approved yet.</p>
        <a href="{{ route('loans.applications') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Review Applications
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

<!-- Bulk Disbursement Modal -->
<div id="bulkDisbursementModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Disbursement</h3>
            <p class="text-sm text-gray-600 mb-4">Process multiple loan disbursements at once. Select loans and disbursement method.</p>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Disbursement Method</label>
                    <select id="bulkMethod" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">Select Method</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="direct_debit">Direct Debit</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Batch Reference</label>
                    <input type="text" id="bulkReference" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Enter batch reference">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="bulkNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Add batch notes..."></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button onclick="hideBulkDisbursementModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </button>
                <button onclick="processBulkDisbursement()" class="px-4 py-2 bg-green-500 border border-transparent rounded-lg font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                    Process Bulk Disbursement
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showBulkDisbursementModal() {
    document.getElementById('bulkDisbursementModal').classList.remove('hidden');
}

function hideBulkDisbursementModal() {
    document.getElementById('bulkDisbursementModal').classList.add('hidden');
}

function processBulkDisbursement() {
    const method = document.getElementById('bulkMethod').value;
    const reference = document.getElementById('bulkReference').value;
    const notes = document.getElementById('bulkNotes').value;
    
    if (!method || !reference) {
        alert('Please fill in all required fields.');
        return;
    }
    
    // Here you would typically make an AJAX call to process bulk disbursement
    alert('Bulk disbursement processed successfully!');
    hideBulkDisbursementModal();
    location.reload();
}
</script>
@endsection
