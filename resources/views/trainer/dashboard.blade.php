<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FitAja') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">

        {{-- ================= SIDEBAR ================= --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r flex flex-col">

            <x-dashboard-header name="{{ auth()->user()->name }}" />

            {{-- Navigation Menu --}}
            <nav class="mt-6 flex-1 overflow-y-auto">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

                {{-- Dashboard (Active) --}}
                <a href="{{ route('trainer.dashboard') }}" class="flex items-center px-4 py-3 bg-blue-50 text-blue-600 border-r-4 border-blue-600 transition-colors">
                    <img src="{{ asset('icons/home.svg') }}" alt="home" class="w-5 h-5 mr-3 text-blue-600">
                    <span class="font-medium">Dashboard</span>
                </a>

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-4">Aktivitas Saya</div>

                {{-- Booking Link --}}
                <a href="{{ route('trainer.bookings') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                    <img src="{{ asset('icons/calendar.svg') }}" alt="booking" class="w-5 h-5 mr-3 text-gray-500">
                    <span>My Bookings</span>
                </a>

                {{-- Kelas Saya Link --}}
                <a href="{{ route('trainer.classes') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                    <img src="{{ asset('icons/class.svg') }}" alt="class" class="w-5 h-5 mr-3 text-gray-500">
                    <span>My Classes</span>
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

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="flex-1 ml-64 bg-gray-100 h-screen overflow-hidden">
            <main class="pt-8 pb-8 px-8 h-full overflow-y-auto scroll-container">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Welcome back, {{ explode(' ', Auth::user()->name)[0] }}!
                    </h1>
                    <p class="text-gray-500">Here's your activity overview and gym updates.</p>
                </div>

                {{-- Statistics Cards (4 Columns) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    {{-- Total Classes --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-50 rounded-lg">
                                <i class="fas fa-dumbbell text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Classes</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalClasses ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Total Bookings --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalBookings ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Today's Sessions (Gabungan Booking + Kelas) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-50 rounded-lg">
                                <i class="fas fa-clock text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Today's Sessions</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $todaySessions ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Upcoming Sessions (Gabungan Booking + Kelas) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-50 rounded-lg">
                                <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Upcoming Sessions</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $upcomingSessions ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Content Grid: Class Schedule & Recent Bookings --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Upcoming Class Schedule --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden h-fit">
                        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">Jadwal Kelas Mendatang</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($upcomingClasses as $class)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('trainer.class.detail', $class->id) }}" class="text-sm font-bold text-gray-900 hover:text-indigo-600 hover:underline">
                                            {{ $class->type }}
                                        </a>
                                        <div class="flex items-center text-sm text-gray-500 mt-0.5">
                                            <i class="far fa-calendar-alt mr-1.5 opacity-70"></i>
                                            {{ \Carbon\Carbon::parse($class->schedule)->format('M d, Y') }}
                                            <span class="mx-2">•</span>
                                            <i class="far fa-clock mr-1.5 opacity-70"></i>
                                            {{ \Carbon\Carbon::parse($class->schedule)->format('H:i') }}
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $class->capacity }} Seats
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="p-12 text-center">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-dumbbell text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada kelas terjadwal.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Recent Bookings --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden h-fit">
                        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">Booking Terbaru</h3>
                            <a href="{{ route('trainer.bookings') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @forelse(($recentBookings ?? []) as $booking)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if($booking->membership->user->image_url)
                                            <img class="w-10 h-10 rounded-full object-cover"
                                                src="{{ $booking->membership->user->image_url }}"
                                                alt="{{ $booking->membership->user->name }}">
                                            @else
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 text-sm font-bold">
                                                    {{ strtoupper(substr($booking->membership->user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-bold text-gray-900">{{ $booking->membership->user->name }}</p>
                                            <div class="flex items-center text-sm text-gray-500 mt-0.5">
                                                <i class="far fa-calendar-alt mr-1.5 opacity-70"></i>
                                                {{ \Carbon\Carbon::parse($booking->date)->format('M d') }}
                                                <span class="mx-2">•</span>
                                                {{ $booking->time }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right hidden sm:block">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $booking->duration }}m
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="far fa-calendar-times text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">No recent bookings</h3>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </main>
        </div>

    </div>
</body>

</html>