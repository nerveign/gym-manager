<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - My Bookings</title>
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
                    <div class="h-10 w-10 rounded-lg flex items-center justify-center bg-indigo-600">
                        <img src="{{ asset('icons/barbell.svg') }}" 
                             alt="Logo"
                             class="w-6 h-6 invert brightness-0">
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">My Bookings</p>
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
                
                <a href="{{ route('trainer.bookings') }}" class="flex items-center px-4 py-3 text-white bg-blue-50 border-r-2 border-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-blue-600 font-medium">Booking Saya</span>
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
                {{-- Header --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Bookings</h1>
                            <p class="text-gray-500">Manage and view all your training sessions</p>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <form method="GET" action="{{ route('trainer.bookings') }}" class="flex flex-wrap gap-4">
                        {{-- Search --}}
                        <div class="flex-1 min-w-64">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search Client</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by client name or email..."
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- Date Filter --}}
                        <div class="min-w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- Status Filter --}}
                        <div class="min-w-40">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Status</option>
                                <option value="today" {{ request('status') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Past</option>
                            </select>
                        </div>

                        {{-- Search Button --}}
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            @if(request()->hasAny(['search', 'date', 'status']))
                                <a href="{{ route('trainer.bookings') }}" class="ml-2 text-gray-500 hover:text-gray-700 px-4 py-2 border border-gray-300 rounded-lg">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Bookings List --}}
                <div class="bg-white rounded-lg shadow">
                    @if($bookings->count() > 0)
                        {{-- Header --}}
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Training Sessions ({{ $bookings->total() }} total)
                            </h3>
                        </div>

                        {{-- Bookings Table --}}
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($bookings as $booking)
                                        <tr class="hover:bg-gray-50">
                                            {{-- Client Info --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
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
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $booking->membership->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $booking->membership->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Date & Time --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }}</div>
                                                <div class="text-sm text-gray-500">{{ $booking->time }}</div>
                                            </td>

                                            {{-- Duration --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-900">{{ $booking->duration }} minutes</span>
                                            </td>

                                            {{-- Contact --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($booking->membership->user->phone)
                                                    <a href="tel:{{ $booking->membership->user->phone }}" 
                                                       class="text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-phone mr-1"></i>{{ $booking->membership->user->phone }}
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">No phone</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $bookings->withQueryString()->links() }}
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="p-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings found</h3>
                            @if(request()->hasAny(['search', 'date', 'status']))
                                <p class="text-gray-500 mb-4">No bookings match your current filters.</p>
                                <a href="{{ route('trainer.bookings') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Clear filters
                                </a>
                            @else
                                <p class="text-gray-500">You don't have any training sessions yet.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </main>
        </div>

    </div>
</body>
</html>
