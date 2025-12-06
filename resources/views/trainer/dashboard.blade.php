<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">

        {{-- FIXED SIDEBAR --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">

            {{-- Header Sidebar --}}
            <div class="h-16 flex items-center px-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">

                    {{-- ==== LOGO YANG DIAMBIL DARI DASHBOARD ADMIN ==== --}}
                    <div class="h-10 w-10 rounded-lg flex items-center justify-center bg-indigo-600">
    <img src="{{ asset('icons/barbell.svg') }}" 
         alt="Logo"
         class="w-6 h-6 invert brightness-0">
</div>

                    <div>
                        <p class="text-sm font-bold text-gray-900">Dashboard</p>
                        <p class="text-xs text-gray-500">
                            Welcome, {{ explode(' ', Auth::user()->name)[0] }}!
                        </p>
                    </div>
                </div>
            </div>

            {{-- Navigation Menu --}}
            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                
                <a href="{{ route('trainer.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-4">Aktivitas Saya</div>
                
                <a href="{{ route('trainer.bookings') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Booking Saya</span>
                </a>
            </nav>

            {{-- User Profile Section --}}
            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <a href="{{ route('trainer.profile.edit') }}" class="flex items-center flex-1 hover:bg-gray-50 rounded-lg p-2 transition-colors">
                    @if(Auth::user()->profile_photo_path)
                        <img class="w-8 h-8 rounded-full object-cover" 
                             src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                             alt="{{ Auth::user()->name }}">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center">
                            <span class="text-white text-xs font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                </a>

                <div>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                            class="flex items-center text-gray-500 hover:text-gray-700 transition-colors p-2 rounded-lg hover:bg-gray-50"
                            title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 ml-64 bg-gray-100">
            <main class="pt-8 pb-8 px-8 h-screen overflow-y-auto">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Welcome back, {{ explode(' ', Auth::user()->name)[0] }}!
                    </h1>
                    <p class="text-gray-500">Here's your activity overview and gym updates.</p>
                </div>

                {{-- Statistics Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    {{-- Total Bookings --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Bookings</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalBookings ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Today's Bookings --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Today's Sessions</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $todayBookings ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Upcoming Bookings --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Upcoming Sessions</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $upcomingBookings ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Bookings --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                            <a href="{{ route('trainer.bookings') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View All
                            </a>
                        </div>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @forelse(($recentBookings ?? []) as $booking)
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if($booking->membership->user->profile_photo_path)
                                                <img class="w-10 h-10 rounded-full object-cover" 
                                                     src="{{ asset('storage/' . $booking->membership->user->profile_photo_path) }}" 
                                                     alt="{{ $booking->membership->user->name }}">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                                    <span class="text-white text-sm font-semibold">
                                                        {{ strtoupper(substr($booking->membership->user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">{{ $booking->membership->user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }} at {{ $booking->time }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Duration</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $booking->duration }} mins</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500">No recent bookings yet</p>
                                <p class="text-sm text-gray-400">Your upcoming sessions will appear here</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>

    </div>
</body>
</html>
