@props(['activeMenu' => ''])

<div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r flex flex-col">
    {{-- Header Sidebar --}}
    <x-dashboard-header name="{{ auth()->user()->name }}" />

    {{-- Navigation Menu --}}
    <nav class="mt-6 flex-1 overflow-y-auto">
        <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

        {{-- Dashboard Link --}}
        <a href="{{ route('trainer.dashboard') }}" 
           class="flex items-center px-4 py-3 {{ $activeMenu === 'dashboard' ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
            <i class="fas fa-home w-5 mr-3 {{ $activeMenu === 'dashboard' ? 'text-blue-600' : 'text-gray-500' }}"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-4">Aktivitas Saya</div>

        {{-- My Bookings Link --}}
        <a href="{{ route('trainer.bookings') }}" 
           class="flex items-center px-4 py-3 {{ $activeMenu === 'bookings' ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
            <i class="fas fa-calendar w-5 mr-3 {{ $activeMenu === 'bookings' ? 'text-blue-600' : 'text-gray-500' }}"></i>
            <span>My Bookings</span>
        </a>

        {{-- My Classes Link --}}
        <a href="{{ route('trainer.classes') }}" 
           class="flex items-center px-4 py-3 {{ $activeMenu === 'classes' ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
            <i class="fas fa-users w-5 mr-3 {{ $activeMenu === 'classes' ? 'text-blue-600' : 'text-gray-500' }}"></i>
            <span>My Classes</span>
        </a>

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Fasilitas</div>

        {{-- Equipment List --}}
        <a href="{{ route('trainer.equipments.index') }}" 
           class="flex items-center px-4 py-3 {{ $activeMenu === 'equipment' ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
            <i class="fas fa-dumbbell w-5 mr-3 {{ $activeMenu === 'equipment' ? 'text-blue-600' : 'text-gray-500' }}"></i>
            <span>Equipment List</span>
        </a>
    </nav>

    {{-- User Profile Section --}}
    <div class="p-4 border-t bg-white flex-shrink-0">
        <div class="flex items-center justify-between">
            <a href="{{ route('trainer.profile.edit') }}" class="flex items-center flex-1 hover:bg-gray-50 rounded-lg p-2 transition-colors group">
                @if(auth()->user()->image_url)
                <img class="w-8 h-8 rounded-full object-cover border border-gray-200"
                    src="{{ auth()->user()->image_url }}"
                    alt="{{ auth()->user()->name }}">
                @else
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center border border-gray-300">
                    <i class="fas fa-user text-gray-400 text-xs"></i>
                </div>
                @endif
                <div class="ml-3 overflow-hidden">
                    <p class="text-sm font-medium text-gray-700 truncate group-hover:text-indigo-600 transition-colors">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </a>

            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex items-center justify-center w-8 h-8 ml-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </div>
</div>
