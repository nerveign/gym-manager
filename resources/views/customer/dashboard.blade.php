<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard | FitAja</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- Fixed Sidebar --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                <x-nav-item text="Home" color="text-zinc-700" src="home.svg" location="customer.dashboard" style="bg-blue-50 border-r-4 border-blue-500" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
                <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
                <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            {{-- User Profile Section --}}
            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                 <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ $user->image_url ?? asset('images/default-user.png') }}" alt="{{ $user->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Customer</p>
                    </div>
                </div>
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center p-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 text-gray-500">
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div class="flex-1 ml-64">
            <main class="pt-6 pb-8 px-6 h-screen overflow-y-auto scroll-container">
                {{-- Header --}}
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Welcome back, {{ Str::words($user->name, 1, '') }}!</h2>
                    <p class="text-gray-600">Here's your activity overview and gym updates.</p>
                </div>

                {{-- === START: NEW DASHBOARD LAYOUT === --}}
                {{-- Baris Atas: 3 Kolom --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    {{-- Kolom 1: Membership Status --}}
                    <div class="lg:col-span-1">
                        @include('customer.partials.membership-card', ['activeMembership' => $activeMembership])
                    </div>

                    {{-- Kolom 2: Enrolled Classes --}}
                    <div class="bg-white p-6 rounded-lg shadow-sm border lg:col-span-1">
                        <div class="flex items-center space-x-4">
                            <div class="size-12 flex items-center justify-center bg-indigo-100 rounded-lg">
                                <i class="fas fa-users text-indigo-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Enrolled Classes</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $enrolledClasses }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom 3: Upcoming Bookings Count --}}
                    <div class="bg-white p-6 rounded-lg shadow-sm border lg:col-span-1">
                        <div class="flex items-center space-x-4">
                            <div class="size-12 flex items-center justify-center bg-teal-100 rounded-lg">
                                <i class="fas fa-calendar-check text-teal-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Upcoming Bookings</p>
                                {{-- Gunakan $upcomingBookingsCount dari controller --}}
                                <p class="text-2xl font-bold text-gray-900">{{ $upcomingBookingsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Baris Bawah: 2 Kolom (50:50) --}}
                {{-- Ubah grid-cols-5 menjadi grid-cols-2 --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Kolom Kiri: Recent Progress --}}
                    {{-- Ubah col-span-3 menjadi col-span-1 --}}
                    <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Progress</h3>
                            <a href="{{ route('customer.progress.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                        </div>
                        {{-- Cek jika ada recent progress --}}
                        @if($recentProgress->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentProgress as $item)
                                    <div class="border-b pb-3 last:border-b-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $item->exercise }}</p>
                                                <p class="text-sm text-gray-500">{{ $item->duration }} minutes</p>
                                            </div>
                                            {{-- Pastikan created_at tidak null sebelum format --}}
                                            <span class="text-xs text-gray-400">{{ $item->created_at ? $item->created_at->diffForHumans() : 'N/A' }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1 truncate">{{ $item->description ?? 'No description.' }}</p>
                                        {{-- Quick Edit/Delete --}}
                                        <div class="flex space-x-2 mt-2">
                                            <a href="{{ route('customer.progress.edit', $item->id) }}" class="text-xs text-blue-500 hover:text-blue-700">Edit</a>
                                            <span class="text-xs text-gray-300">|</span>
                                            <form action="{{ route('customer.progress.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this progress record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-xs text-red-500 hover:text-red-700">Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Tampilan jika tidak ada progress --}}
                            <div class="text-center py-4 text-gray-500">
                                <p>No recent progress recorded.</p>
                                <a href="{{ route('customer.progress.create') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800 font-medium">
                                    Add Your First Progress
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Kolom Kanan: Upcoming Bookings Schedule --}}
                    {{-- Ubah col-span-2 menjadi col-span-1 --}}
                    <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Upcoming Schedule</h3>
                            <a href="{{ route('customer.bookings.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                        </div>
                        {{-- Cek jika ada data booking mendatang --}}
                        @if($upcomingBookingsData->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingBookingsData as $booking)
                                    <div class="flex items-center space-x-4 border-b pb-3 last:border-b-0">
                                        <div class="size-10 flex items-center justify-center bg-blue-100 rounded-lg text-blue-600">
                                             <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">
                                                {{-- Format Tanggal dan Waktu Booking --}}
                                                {{ \Carbon\Carbon::parse($booking->date)->format('D, d M') }}
                                                at {{ \Carbon\Carbon::parse($booking->time)->format('H:i') }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{-- Tampilkan Nama Trainer (cek null) dan Durasi --}}
                                                With {{ $booking->trainer->name ?? 'N/A' }} ({{ $booking->duration }} mins)
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                             {{-- Tampilan jika tidak ada booking mendatang --}}
                             <div class="text-center py-4 text-gray-500">
                                <p>No upcoming bookings found.</p>
                                <a href="{{ route('customer.bookings.create') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800 font-medium">
                                    Book a session now!
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- === END: NEW DASHBOARD LAYOUT === --}}
            </main>
        </div>
    </div>
</body>
</html>