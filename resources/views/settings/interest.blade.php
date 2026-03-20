@extends('layouts.app')

@section('title', 'Interest & Penalties')

@section('page-title', 'INTEREST & PENALTIES')
@section('page-description', 'Configure interest rates and penalty settings')

@section('content')
<div class="space-y-6">
    <!-- Interest Settings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Interest Rate Configuration</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Interest Rate (%)</label>
                    <input type="number" step="0.1" value="{{ $settings['default_rate'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penalty Rate (%)</label>
                    <input type="number" step="0.1" value="{{ $settings['penalty_rate'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Late Fee Amount</label>
                    <input type="number" value="{{ $settings['late_fee_amount'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Grace Period (Days)</label>
                    <input type="number" value="{{ $settings['grace_period_days'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Compound Frequency</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="daily" {{ $settings['compound_frequency'] === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ $settings['compound_frequency'] === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ $settings['compound_frequency'] === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="annually" {{ $settings['compound_frequency'] === 'annually' ? 'selected' : '' }}>Annually</option>
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <button class="px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Save Settings
                </button>
            </div>
        </div>
    </div>

    <!-- Credit Score Tiers -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Credit Score Interest Tiers</h3>
                <button class="px-4 py-2 bg-green-500 border border-transparent rounded-lg font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                    Add Tier
                </button>
            </div>
            
            <div class="space-y-4">
                @foreach($rateTiers as $tier)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">{{ $tier['description'] }}</h4>
                            <p class="text-sm text-gray-500">Score: {{ $tier['credit_score_min'] }} - {{ $tier['credit_score_max'] }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-lg font-bold text-blue-600">{{ $tier['interest_rate'] }}%</span>
                            <button class="text-blue-600 hover:text-blue-900">Edit</button>
                            <button class="text-red-600 hover:text-red-900">Delete</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Min Score</label>
                            <input type="number" value="{{ $tier['credit_score_min'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Max Score</label>
                            <input type="number" value="{{ $tier['credit_score_max'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
