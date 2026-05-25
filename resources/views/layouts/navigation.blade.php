@php
    $user = Auth::user();
    $roleLabel = match($user->role ?? '') {
        'admin'   => 'Product Owner',
        'manager' => 'Project Manager',
        default   => 'Developer',
    };
    $dashboardRoute = match($user->role ?? '') {
        'admin'   => 'admin.dashboard',
        'manager' => 'manager.dashboard',
        default   => 'developer.dashboard',
    };
@endphp

<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

        {{-- Logo & Role Label --}}
        <div class="flex items-center gap-3">
            <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                     style="background-color:#780000">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806
                                 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806
                                 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946
                                 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946
                                 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806
                                 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806
                                 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946
                                 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946
                                 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <span class="font-bold text-lg tracking-tight" style="color:#780000">AnoReward</span>
            </a>
            <span class="hidden sm:inline text-gray-300 text-lg">|</span>
            <span class="hidden sm:inline text-sm font-medium text-gray-500">{{ $roleLabel }}</span>
        </div>

        {{-- User Info & Logout --}}
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-gray-700">{{ $user->name }}</p>
                <p class="text-xs text-gray-400">{{ $roleLabel }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-sm font-medium text-gray-500 hover:text-red-700 transition-colors px-3 py-1.5 rounded-lg hover:bg-red-50">
                    Keluar
                </button>
            </form>
        </div>

    </div>
</nav>