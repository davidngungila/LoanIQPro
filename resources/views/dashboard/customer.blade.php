@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('page-title', 'My Loans')
@section('page-description', 'Manage your loan applications and payments')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <div class="text-center">
        <svg class="w-16 h-16 text-lime-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Customer Dashboard</h2>
        <p class="text-gray-600">Customer dashboard is ready for loan management features.</p>
    </div>
</div>
@endsection
