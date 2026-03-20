@extends('layouts.app')

@section('title', 'Bulk Disbursement')

@section('page-title', 'BULK DISBURSEMENT')
@section('page-description', 'Process multiple loan disbursements')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Ready for Bulk</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['ready_for_bulk']) }}</p>
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
                    <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_bulk_amount'], 0) }}</p>
                    <p class="text-xs text-blue-600 mt-1">Ready to disburse</p>
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
                    <p class="text-sm text-gray-600 mb-1">Processed Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['bulk_processed_today']) }}</p>
                    <p class="text-xs text-purple-600 mt-1">Bulk disbursements</p>
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
                    <p class="text-sm text-gray-600 mb-1">Avg Batch Size</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['avg_bulk_size']) }}</p>
                    <p class="text-xs text-yellow-600 mt-1">Loans per batch</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Disbursement Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('disbursements.bulk-process') }}" class="p-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Batch Details</h3>
                    
                    <div>
                        <label for="disbursement_method" class="block text-sm font-medium text-gray-700 mb-1">Disbursement Method</label>
                        <select id="disbursement_method" name="disbursement_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <option value="">Select Method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="check">Check</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="direct_debit">Direct Debit</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="batch_reference" class="block text-sm font-medium text-gray-700 mb-1">Batch Reference</label>
                        <input type="text" id="batch_reference" name="batch_reference" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Enter batch reference">
                    </div>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Add batch notes..."></textarea>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Select Loans</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Branch</label>
                        <select id="branch_filter" onchange="filterLoans()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Loans to Disburse</label>
                        <div class="border border-gray-300 rounded-lg p-3 max-h-64 overflow-y-auto">
                            @foreach($loans as $loan)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                <div class="flex items-center">
                                    <input type="checkbox" name="loan_ids[]" value="{{ $loan->id }}" class="loan-checkbox h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded" data-branch="{{ $loan->branch_id }}">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">#{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }} - {{ $loan->customer->full_name }}</div>
                                        <div class="text-sm text-gray-500">${{ number_format($loan->amount, 0) }} - {{ $loan->branch->name }}</div>
                                    </div>
                                </div>
                                <div class="text-sm font-medium text-gray-900">${{ number_format($loan->amount, 0) }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="selectAll()" class="text-sm text-blue-600 hover:text-blue-800">Select All</button>
                        <button type="button" onclick="deselectAll()" class="text-sm text-gray-600 hover:text-gray-800">Deselect All</button>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <div class="flex items-center mr-4">
                    <span class="text-sm text-gray-600">Selected Amount:</span>
                    <span id="selected-total" class="text-lg font-bold text-gray-900 ml-2">$0</span>
                </div>
                <button type="submit" class="px-6 py-2 bg-green-500 border border-transparent rounded-lg font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                    Process Bulk Disbursement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterLoans() {
    const branchId = document.getElementById('branch_filter').value;
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    
    checkboxes.forEach(checkbox => {
        const loanBranch = checkbox.dataset.branch;
        if (branchId === '' || loanBranch === branchId) {
            checkbox.closest('.flex').style.display = 'flex';
        } else {
            checkbox.closest('.flex').style.display = 'none';
        }
    });
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    checkboxes.forEach(checkbox => {
        if (checkbox.closest('.flex').style.display !== 'none') {
            checkbox.checked = true;
        }
    });
    updateTotal();
}

function deselectAll() {
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateTotal();
}

function updateTotal() {
    const checkboxes = document.querySelectorAll('.loan-checkbox:checked');
    let total = 0;
    
    checkboxes.forEach(checkbox => {
        const amountText = checkbox.closest('.flex').querySelector('.text-gray-900:last-child').textContent;
        const amount = parseFloat(amountText.replace(/[$,]/g, ''));
        total += amount;
    });
    
    document.getElementById('selected-total').textContent = '$' + total.toLocaleString();
}

// Add event listeners to checkboxes
document.querySelectorAll('.loan-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateTotal);
});

// Initialize total
updateTotal();
</script>
@endsection
