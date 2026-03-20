@extends('layouts.app')

@section('title', 'Guarantor Dashboard')

@section('page-title', 'Guaranteed Loans')
@section('page-description', 'View loans you have guaranteed')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <div class="text-center">
        <svg class="w-16 h-16 text-orange-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Guarantor Dashboard</h2>
        <p class="text-gray-600">Guarantor dashboard is ready for loan guarantee features.</p>
    </div>
</div>
@endsection
