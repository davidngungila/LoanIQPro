@extends('layouts.app')

@section('title', 'Create Role')

@section('page-title', 'CREATE ROLE')
@section('page-description', 'Add a new role with permissions')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('roles.store') }}" class="p-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name *</label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                               value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Role Slug *</label>
                        <input type="text" id="slug" name="slug" required maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                               value="{{ old('slug') }}"
                               placeholder="role-slug">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Unique identifier for the role</p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" maxlength="1000"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mt-6">
                    <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Access Level *</label>
                    <select id="level" name="level" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">Select Level</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('level') == $i ? 'selected' : '' }}>
                                Level {{ $i }} - {{ $i <= 3 ? 'High' : ($i <= 7 ? 'Medium' : 'Low') }} Priority
                            </option>
                        @endfor
                    </select>
                    @error('level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Lower numbers have higher priority (1 = highest)</p>
                </div>
            </div>

            <!-- Permissions -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Permissions</h3>
                
                @foreach($permissions as $category => $categoryPermissions)
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-800 mb-3 capitalize">{{ str_replace('_', ' ', $category) }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($categoryPermissions as $permission => $label)
                        <div class="flex items-center">
                            <input type="checkbox" id="permission_{{ $permission }}" name="permissions[]" value="{{ $permission }}"
                                   class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500"
                                   {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}>
                            <label for="permission_{{ $permission }}" class="ml-2 block text-sm text-gray-700">
                                {{ $label }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
                
                @error('permissions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Settings -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings</h3>
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
                           class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Active Role
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500">Inactive roles won't be available for user assignment</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('roles.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                    Create Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
