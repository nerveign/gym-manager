<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Trainer - FitAja</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen">

        {{-- ================= SIDEBAR TRAINER ================= --}}
        <x-trainer-sidebar activeMenu="dashboard" />

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="flex-1 ml-64 bg-gray-100 h-screen overflow-hidden">
            <main class="pt-8 pb-8 px-8 h-full overflow-y-auto scroll-container">

                {{-- Welcome Section --}}
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
                    <p class="text-gray-500">Here's your activity overview and gym updates.</p>
                </div>

                {{-- Stats Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    {{-- Card 1: Total Classes --}}
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center">
                        <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 mr-4">
                            <img src="{{ asset('icons/class.svg') }}" class="w-6 h-6" alt="icon">
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Total Classes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalClasses }}</p>
                        </div>
                    </div>

                    {{-- Card 2: Total Bookings --}}
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center">
                        <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 mr-4">
                            <img src="{{ asset('icons/calendar.svg') }}" class="w-6 h-6" alt="icon">
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Total Bookings</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
                        </div>
                    </div>

                    {{-- Card 3: Today's Sessions --}}
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center">
                        <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center text-green-600 mr-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Today's Sessions</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $todaySessions }}</p>
                        </div>
                    </div>

                    {{-- Card 4: Upcoming Sessions --}}
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center">
                        <div class="w-12 h-12 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600 mr-4">
                            <i class="fas fa-hourglass-half text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Upcoming Sessions</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $upcomingSessions }}</p>
                        </div>
                    </div>
                </div>

                {{-- Content Split --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    {{-- Left Column: Jadwal Kelas Mendatang --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-900">Jadwal Kelas Mendatang</h3>
                        </div>
                        <div class="p-6">
                            @if($upcomingClasses->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingClasses as $class)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $class->type }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($class->schedule)->format('M d, Y') }} •
                                            <i class="far fa-clock ml-1 mr-1"></i> {{ \Carbon\Carbon::parse($class->schedule)->format('H:i') }}
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-lg">
                                        {{ $class->capacity - $class->class_members_count }} Seats
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8 text-gray-500 text-sm">
                                Tidak ada jadwal kelas mendatang.
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Right Column: Booking Terbaru --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900">Booking Terbaru</h3>
                            <a href="{{ route('trainer.bookings') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">View All &rarr;</a>
                        </div>
                        <div class="p-6">
                            @if($recentBookings->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentBookings as $booking)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @if($booking->membership->user->image_url)
                                        <img src="{{ $booking->membership->user->image_url }}" class="w-10 h-10 rounded-full object-cover mr-3" alt="user">
                                        @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                            <span class="text-xs font-bold text-gray-500">{{ substr($booking->membership->user->name, 0, 1) }}</span>
                                        </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $booking->membership->user->name }}</p>
                                            <p class="text-xs text-gray-500">
                                                <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($booking->date)->format('M d') }} • {{ $booking->time }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-50 text-blue-600 rounded-lg border border-blue-100">
                                        {{ $booking->duration }}m
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8 text-gray-500 text-sm">
                                Belum ada booking terbaru.
                            </div>
                            @endif
                        </div>
                    </div>

                </div>

            </main>
        </div>
    </div>
</body>

</html>