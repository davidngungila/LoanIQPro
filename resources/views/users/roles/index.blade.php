@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('page-title', 'ROLES & PERMISSIONS')
@section('page-description', 'Manage user roles and system permissions')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Roles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_roles'] }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $stats['active_roles'] }} active</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Users with Roles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['users_with_roles'] }}</p>
                    <p class="text-xs text-red-600 mt-1">{{ $stats['users_without_roles'] }} unassigned</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Highest Level</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['highest_level'] }}</p>
                    <p class="text-xs text-blue-600 mt-1">Level {{ $stats['lowest_level'] }} minimum</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Avg Users/Role</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_roles'] > 0 ? round($stats['users_with_roles'] / $stats['total_roles'], 1) : 0 }}</p>
                    <p class="text-xs text-green-600 mt-1">Per role</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-semibold text-gray-900">All Roles</h3>
                <span class="text-sm text-gray-500">{{ $roles->total() }} roles</span>
            </div>
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('roles.index') }}" class="flex items-center space-x-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search roles..." class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    
                    <select name="level" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">All Levels</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ request('level') == $i ? 'selected' : '' }}>Level {{ $i }}</option>
                        @endfor
                    </select>
                    
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Filter
                    </button>
                </form>
                
                <a href="{{ route('roles.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Role
                </a>
            </div>
        </div>
    </div>

    <!-- Roles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($roles as $role)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $role->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $role->slug }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($role->is_active)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactive</span>
                        @endif
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Lvl {{ $role->level }}</span>
                    </div>
                </div>

                @if($role->description)
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $role->description }}</p>
                @endif

                <div class="space-y-3 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Users Assigned</span>
                        <span class="text-gray-900">{{ $role->users_count }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Permissions</span>
                        <span class="text-gray-900">{{ count(json_decode($role->permissions ?? '[]', true)) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Access Level</span>
                        <span class="text-gray-900">Level {{ $role->level }}</span>
                    </div>
                </div>

                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-600 mb-2">Key Permissions:</div>
                    <div class="flex flex-wrap gap-1">
                        @php
                            $permissions = json_decode($role->permissions ?? '[]', true);
                            $keyPermissions = array_slice($permissions, 0, 3);
                        @endphp
                        @foreach($keyPermissions as $permission)
                            <span class="px-2 py-1 text-xs bg-white rounded text-gray-700">{{ str_replace('_', ' ', ucwords($permission)) }}</span>
                        @endforeach
                        @if(count($permissions) > 3)
                            <span class="px-2 py-1 text-xs bg-white rounded text-gray-500">+{{ count($permissions) - 3 }} more</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        @if($role->users_count > 0)
                            <span class="text-xs text-green-600 font-medium">{{ $role->users_count }} users</span>
                        @else
                            <span class="text-xs text-gray-500 font-medium">No users</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('roles.show', $role) }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">View</a>
                        <a href="{{ route('roles.edit', $role) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($roles->isEmpty())
    <div class="bg-white rounded-xl shadow-sm p-12 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No roles found</h3>
        <p class="text-gray-500 mb-6">Get started by creating your first role.</p>
        <a href="{{ route('roles.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create Role
        </a>
    </div>
    @endif

    <!-- Pagination -->
    @if($roles->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $roles->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $roles->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $roles->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $roles->total() }}</span>
                        results
                    </p>
                </div>
                <div>
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
