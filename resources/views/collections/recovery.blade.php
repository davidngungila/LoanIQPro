@extends('layouts.app')

@section('title', 'Recovery Management')

@section('page-title', 'RECOVERY MANAGEMENT')
@section('page-description', 'Manage loan recovery and collection processes')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Recovery Cases</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_recovery_cases']) }}</p>
                    <p class="text-xs text-blue-600 mt-1">Active cases</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Critical Cases</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['critical_cases']) }}</p>
                    <p class="text-xs text-red-600 mt-1">90+ days overdue</p>
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
                    <p class="text-sm text-gray-600 mb-1">Recovery Rate</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['recovery_rate'] }}%</p>
                    <p class="text-xs text-green-600 mt-1">Success rate</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Recovery Amount</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_recovery_amount'], 0) }}</p>
                    <p class="text-xs text-purple-600 mt-1">At risk</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-semibold text-gray-900">Recovery Cases</h3>
                <span class="text-sm text-gray-500">{{ $loans->total() }} cases</span>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                <form method="GET" action="{{ route('collections.recovery') }}" class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                    <select name="severity" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">All Severity</option>
                        <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical (90+ days)</option>
                        <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>High (60-90 days)</option>
                        <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Medium (30-60 days)</option>
                    </select>
                    
                    <select name="branch_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    
                    <select name="collector_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">All Collectors</option>
                        @foreach($collectors as $collector)
                        <option value="{{ $collector->id }}" {{ request('collector_id') == $collector->id ? 'selected' : '' }}>{{ $collector->name }}</option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Filter
                    </button>
                </form>
                
                <a href="{{ route('collections.collectors') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Manage Collectors
                </a>
            </div>
        </div>
    </div>

    <!-- Recovery Cases Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overdue Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Collector</th>
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
                            <div class="text-sm font-medium text-gray-900">${{ number_format($loan->amount, 0) }}</div>
                            <div class="text-sm text-gray-500">Outstanding: ${{ number_format($loan->outstanding_balance, 0) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($loan->first_payment_date)
                                    Due: {{ $loan->first_payment_date->format('M d, Y') }}
                                @else
                                    Not set
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">
                                @if($loan->first_payment_date)
                                    {{ $loan->first_payment_date->diffInDays(now()) }} days overdue
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($loan->first_payment_date)
                                @php
                                    $daysOverdue = $loan->first_payment_date->diffInDays(now());
                                @endphp
                                @if($daysOverdue > 90)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Critical
                                    </span>
                                @elseif($daysOverdue > 60)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        High
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Medium
                                    </span>
                                @endif
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Unknown
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select class="text-sm border-gray-300 rounded focus:ring-yellow-500" onchange="assignCollector({{ $loan->id }}, this.value)">
                                <option value="">Unassigned</option>
                                @foreach($collectors as $collector)
                                <option value="{{ $collector->id }}">{{ $collector->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form method="POST" action="{{ route('collections.assign-collector') }}" class="inline">
                                @csrf
                                <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                                <input type="hidden" name="collector_id" value="">
                                <button type="submit" class="text-blue-600 hover:text-blue-900">Assign</button>
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

<script>
function assignCollector(loanId, collectorId) {
    if (collectorId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("collections.assign-collector") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const loanIdInput = document.createElement('input');
        loanIdInput.type = 'hidden';
        loanIdInput.name = 'loan_id';
        loanIdInput.value = loanId;
        
        const collectorIdInput = document.createElement('input');
        collectorIdInput.type = 'hidden';
        collectorIdInput.name = 'collector_id';
        collectorIdInput.value = collectorId;
        
        form.appendChild(csrfToken);
        form.appendChild(loanIdInput);
        form.appendChild(collectorIdInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
