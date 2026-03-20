@php
    $user = Auth::user();
    $highestRole = $user->roles()->orderBy('level', 'desc')->first();
@endphp

<!-- Top Navigation -->
<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Page Title -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Iosevka Charon', monospace;">@yield('page-title', 'Dashboard')</h1>
                <p class="text-sm text-gray-600">@yield('page-description', 'Welcome back')</p>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <div class="relative">
                    <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-600">{{ $highestRole->name ?? 'User' }}</p>
                    </div>
                    
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                        <span class="text-gray-600 font-medium">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    </div>
                    
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Branch Info (if applicable) -->
                @if($user->branch && $highestRole && in_array($highestRole->slug, ['super-admin', 'admin']))
                    <div class="hidden lg:block text-right">
                        <p class="text-xs text-gray-500">Branch</p>
                        <p class="text-sm font-medium text-gray-900">{{ $user->branch->name }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>
