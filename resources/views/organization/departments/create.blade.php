@extends('layouts.app')

@section('title', 'Create Department')

@section('page-title', 'CREATE DEPARTMENT')
@section('page-description', 'Add a new department to the organization')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('organization.departments.store') }}" class="p-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Department Name *</label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                               value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Department Code *</label>
                        <input type="text" id="code" name="code" required maxlength="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                               value="{{ old('code') }}">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" maxlength="1000"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Leadership Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Leadership Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="head_of_department" class="block text-sm font-medium text-gray-700 mb-2">Head of Department</label>
                        <input type="text" id="head_of_department" name="head_of_department" maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                               value="{{ old('head_of_department') }}"
                               placeholder="Enter the name of the department head">
                        @error('head_of_department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="head_email" class="block text-sm font-medium text-gray-700 mb-2">Head Email</label>
                        <input type="email" id="head_email" name="head_email" maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                               value="{{ old('head_email') }}"
                               placeholder="head@department.com">
                        @error('head_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="head_phone" class="block text-sm font-medium text-gray-700 mb-2">Head Phone</label>
                        <input type="tel" id="head_phone" name="head_phone" maxlength="20"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                               value="{{ old('head_phone') }}"
                               placeholder="+1 (555) 123-4567">
                        @error('head_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Budget Allocation</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" id="budget" name="budget" min="0" step="0.01"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                   value="{{ old('budget') }}"
                                   placeholder="0.00">
                        </div>
                        @error('budget')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Annual budget allocation for this department</p>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings</h3>
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
                           class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Active Department
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500">Inactive departments won't be available for user assignment</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('organization.departments.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                    Create Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
