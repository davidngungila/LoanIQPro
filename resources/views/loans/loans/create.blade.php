@extends('layouts.app')

@section('title', 'Create Loan')

@section('page-title', 'CREATE LOAN')
@section('page-description', 'Create a new loan application')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('loans.store') }}" class="p-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Customer Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Customer Information</h3>
                    
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Select Customer</label>
                        <select id="customer_id" name="customer_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', request('customer_id')) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->full_name }} - {{ $customer->email }}
                            </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                        <select id="branch_id" name="branch_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }} - {{ $branch->city }}
                            </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="loan_officer_id" class="block text-sm font-medium text-gray-700 mb-1">Loan Officer</label>
                        <select id="loan_officer_id" name="loan_officer_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <option value="">Select Loan Officer</option>
                            @foreach($loanOfficers as $officer)
                            <option value="{{ $officer->id }}" {{ old('loan_officer_id') == $officer->id ? 'selected' : '' }}>
                                {{ $officer->name }} - {{ $officer->email }}
                            </option>
                            @endforeach
                        </select>
                        @error('loan_officer_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="loan_type" class="block text-sm font-medium text-gray-700 mb-1">Loan Type</label>
                        <select id="loan_type" name="loan_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <option value="">Select Loan Type</option>
                            <option value="personal" {{ old('loan_type') == 'personal' ? 'selected' : '' }}>Personal Loan</option>
                            <option value="business" {{ old('loan_type') == 'business' ? 'selected' : '' }}>Business Loan</option>
                            <option value="mortgage" {{ old('loan_type') == 'mortgage' ? 'selected' : '' }}>Mortgage</option>
                            <option value="auto" {{ old('loan_type') == 'auto' ? 'selected' : '' }}>Auto Loan</option>
                            <option value="education" {{ old('loan_type') == 'education' ? 'selected' : '' }}>Education Loan</option>
                            <option value="home_equity" {{ old('loan_type') == 'home_equity' ? 'selected' : '' }}>Home Equity Loan</option>
                        </select>
                        @error('loan_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Loan Details -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Loan Details</h3>
                    
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Loan Amount</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="100" max="1000000" required class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="interest_rate" class="block text-sm font-medium text-gray-700 mb-1">Interest Rate (%)</label>
                        <div class="relative">
                            <input type="number" id="interest_rate" name="interest_rate" value="{{ old('interest_rate') }}" step="0.01" min="0" max="50" required class="w-full pr-8 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <span class="absolute right-3 top-2 text-gray-500">%</span>
                        </div>
                        @error('interest_rate')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="term_months" class="block text-sm font-medium text-gray-700 mb-1">Loan Term (Months)</label>
                        <input type="number" id="term_months" name="term_months" value="{{ old('term_months') }}" min="1" max="360" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        @error('term_months')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Loan Purpose</label>
                        <textarea id="purpose" name="purpose" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Describe the purpose of this loan...">{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="collateral_description" class="block text-sm font-medium text-gray-700 mb-1">Collateral Description (Optional)</label>
                        <textarea id="collateral_description" name="collateral_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Describe any collateral for this loan...">{{ old('collateral_description') }}</textarea>
                        @error('collateral_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Payment Calculation Preview -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Calculation Preview</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Payment</label>
                        <div class="text-lg font-semibold text-gray-900" id="monthly-payment-preview">$0.00</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Payment</label>
                        <div class="text-lg font-semibold text-gray-900" id="total-payment-preview">$0.00</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Interest</label>
                        <div class="text-lg font-semibold text-gray-900" id="total-interest-preview">$0.00</div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Information -->
            <div class="mt-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Additional Information</h3>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Add any additional notes about this loan...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('loans.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                    Create Loan Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function calculateLoanPayment() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const rate = parseFloat(document.getElementById('interest_rate').value) || 0;
    const term = parseInt(document.getElementById('term_months').value) || 0;
    
    if (amount > 0 && rate > 0 && term > 0) {
        const monthlyRate = rate / 100 / 12;
        const monthlyPayment = amount * (monthlyRate * Math.pow(1 + monthlyRate, term)) / (Math.pow(1 + monthlyRate, term) - 1);
        const totalPayment = monthlyPayment * term;
        const totalInterest = totalPayment - amount;
        
        document.getElementById('monthly-payment-preview').textContent = '$' + monthlyPayment.toFixed(2);
        document.getElementById('total-payment-preview').textContent = '$' + totalPayment.toFixed(2);
        document.getElementById('total-interest-preview').textContent = '$' + totalInterest.toFixed(2);
    } else {
        document.getElementById('monthly-payment-preview').textContent = '$0.00';
        document.getElementById('total-payment-preview').textContent = '$0.00';
        document.getElementById('total-interest-preview').textContent = '$0.00';
    }
}

// Add event listeners for real-time calculation
document.getElementById('amount').addEventListener('input', calculateLoanPayment);
document.getElementById('interest_rate').addEventListener('input', calculateLoanPayment);
document.getElementById('term_months').addEventListener('input', calculateLoanPayment);

// Initialize with default values
calculateLoanPayment();
</script>
@endsection
