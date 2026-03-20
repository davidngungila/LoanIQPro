@php
    $user = Auth::user();
    $highestRole = $user->roles()->orderBy('level', 'desc')->first();
    $sidebarMenu = $highestRole ? $highestRole->getSidebarMenu() : [];
@endphp

<!-- Sidebar -->
<aside class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-yellow-400 to-yellow-500 text-white shadow-xl z-40 flex flex-col">
    <!-- Logo -->
    <div class="p-6 border-b border-yellow-600 flex-shrink-0">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-white logo-text">LoanIQ Pro</h1>
                <p class="text-xs text-yellow-100">{{ $highestRole->name ?? 'User' }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto custom-scrollbar">
        @foreach($sidebarMenu as $item)
            <div class="nav-item">
                @if(isset($item['children']) && count($item['children']) > 0)
                    <!-- Dropdown Menu -->
                    <div class="dropdown">
                        @php
                            $hasActiveChild = false;
                            if (isset($item['children'])) {
                                foreach ($item['children'] as $child) {
                                    if (Route::has($child['route']) && request()->routeIs($child['route'])) {
                                        $hasActiveChild = true;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <button class="w-full flex items-center justify-between px-4 py-3 text-left rounded-lg {{ $hasActiveChild ? 'bg-yellow-600' : 'hover:bg-yellow-600' }} transition-colors duration-200 dropdown-toggle" onclick="toggleDropdown(this)">
                            <div class="flex items-center space-x-3">
                                @if($item['icon'])
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <x-lucide-icon name="{{ $item['icon'] }}" />
                                    </svg>
                                @endif
                                <span class="font-medium">{{ $item['title'] }}</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform duration-200 dropdown-arrow {{ $hasActiveChild ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu {{ $hasActiveChild ? '' : 'hidden' }} mt-1 space-y-1">
                            @foreach($item['children'] as $child)
                                @if(Route::has($child['route']))
                                    @php
                                        $isActive = request()->routeIs($child['route']);
                                    @endphp
                                    <a href="{{ route($child['route']) }}" class="block pl-12 pr-4 py-3 text-sm {{ $isActive ? 'bg-yellow-600 bg-opacity-50 text-white' : 'text-black hover:bg-yellow-600 hover:bg-opacity-50' }} rounded transition-colors duration-200">
                                        {{ $child['title'] }}
                                    </a>
                                @else
                                    <span class="block pl-12 pr-4 py-3 text-sm text-black opacity-60 cursor-not-allowed">
                                        {{ $child['title'] }} (Coming Soon)
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Single Menu Item -->
                    @if(Route::has($item['route']))
                        @php
                            $isActive = request()->routeIs($item['route']);
                        @endphp
                        <a href="{{ route($item['route']) }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ $isActive ? 'bg-yellow-600' : 'hover:bg-yellow-600' }} transition-colors duration-200">
                            @if($item['icon'])
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <x-lucide-icon name="{{ $item['icon'] }}" />
                                </svg>
                            @endif
                            <span class="font-medium">{{ $item['title'] }}</span>
                        </a>
                    @else
                        <div class="flex items-center space-x-3 px-4 py-3 rounded-lg opacity-50 cursor-not-allowed">
                            @if($item['icon'])
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <x-lucide-icon name="{{ $item['icon'] }}" />
                                </svg>
                            @endif
                            <span class="font-medium">{{ $item['title'] }} (Coming Soon)</span>
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    </nav>
</aside>

<script>
// Dropdown functionality
function toggleDropdown(button) {
    console.log('Dropdown clicked'); // Debug log
    const dropdown = button.closest('.dropdown');
    const menu = dropdown.querySelector('.dropdown-menu');
    const arrow = dropdown.querySelector('.dropdown-arrow');
    
    // Close other dropdowns
    document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
        if (otherMenu !== menu) {
            otherMenu.classList.add('hidden');
            otherMenu.closest('.dropdown').querySelector('.dropdown-arrow').classList.remove('rotate-180');
        }
    });
    
    // Toggle current dropdown
    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
        document.querySelectorAll('.dropdown-arrow').forEach(arrow => {
            arrow.classList.remove('rotate-180');
        });
    }
});

// Initialize dropdowns when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dropdowns initialized'); // Debug log
});
</script>
