@extends('layouts.app')

@section('title', 'Accountant Dashboard')

@section('page-title', 'Financial Management')
@section('page-description', 'Manage financial operations and reports')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <div class="text-center">
        <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Financial Dashboard</h2>
        <p class="text-gray-600">Accountant dashboard is ready for financial management features.</p>
    </div>
</div>
@endsection
