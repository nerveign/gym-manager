<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FitAja') }} - My Bookings</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">

        {{-- FIXED SIDEBAR --}}
        <x-trainer-sidebar activeMenu="bookings" />

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 ml-64 bg-gray-100 h-screen overflow-hidden">
            <main class="pt-8 pb-8 px-8 h-full overflow-y-auto scroll-container">
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
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
                    <form method="GET" action="{{ route('trainer.bookings') }}" class="flex flex-wrap gap-4">
                        {{-- Search --}}
                        <div class="flex-1 min-w-64">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search Client</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search by client name or email..."
                                    class="w-full pl-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">
                            </div>
                        </div>

                        {{-- Date Filter --}}
                        <div class="min-w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">
                        </div>

                        {{-- Status Filter --}}
                        <div class="min-w-40">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">
                                <option value="">All Status</option>
                                <option value="today" {{ request('status') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Past</option>
                            </select>
                        </div>

                        {{-- Search Button --}}
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                            @if(request()->hasAny(['search', 'date', 'status']))
                            <a href="{{ route('trainer.bookings') }}" class="ml-2 text-gray-500 hover:text-gray-700 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                Clear
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Bookings List --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    @if($bookings->count() > 0)
                    {{-- Header --}}
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Training Sessions <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $bookings->total() }} total</span>
                        </h3>
                    </div>

                    {{-- Bookings Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    {{-- Client Info --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($booking->membership->user->image_url)
                                            <img class="w-10 h-10 rounded-full object-cover border border-gray-200"
                                                src="{{ $booking->membership->user->image_url }}"
                                                alt="{{ $booking->membership->user->name }}">
                                            @else
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                                <span class="text-white text-sm font-bold">
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
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }}
                                            </span>
                                            <span class="text-sm text-gray-500 flex items-center mt-1">
                                                <i class="far fa-clock mr-1.5 text-xs"></i>
                                                {{ $booking->time }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Duration --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-stopwatch mr-1.5"></i>
                                            {{ $booking->duration }} mins
                                        </span>
                                    </td>

                                    {{-- Contact --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($booking->membership->user->phone)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $booking->membership->user->phone)) }}"
                                            target="_blank"
                                            class="inline-flex items-center text-green-600 hover:text-green-800 font-medium transition-colors">
                                            <i class="fab fa-whatsapp text-lg mr-2"></i>
                                            {{ $booking->membership->user->phone }}
                                        </a>
                                        @else
                                        <span class="text-gray-400 italic">No phone</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $bookings->withQueryString()->links() }}
                    </div>
                    @else
                    {{-- Empty State --}}
                    <div class="p-16 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="far fa-calendar-times text-gray-400 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No bookings found</h3>
                        @if(request()->hasAny(['search', 'date', 'status']))
                        <p class="text-gray-500 mb-6 max-w-sm mx-auto">We couldn't find any sessions matching your filters. Try adjusting your search criteria.</p>
                        <a href="{{ route('trainer.bookings') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Clear All Filters
                        </a>
                        @else
                        <p class="text-gray-500 max-w-sm mx-auto">You don't have any training sessions scheduled yet.</p>
                        @endif
                    </div>
                    @endif
                </div>
            </main>
        </div>

    </div>
</body>

</html>