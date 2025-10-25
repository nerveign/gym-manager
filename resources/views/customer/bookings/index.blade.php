<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | Customer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- Fixed Sidebar (Sidebar) --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            {{-- Menggunakan auth() jika $user tidak selalu ada --}}
            <x-dashboard-header name="{{ $user->name ?? auth()->user()->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

                {{-- Nav-Item untuk Home (INACTIVE) --}}
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                {{-- Nav-Item untuk Progress Tracking (INACTIVE) --}}
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />

                {{-- Nav-Item untuk My Bookings (ACTIVE) --}}
                <x-nav-item text="My Bookings" color="text-zinc-700" src="calendar.svg" location="customer.bookings.index" style="bg-blue-50 border-r-4 border-blue-500" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>

                {{-- Nav-Item Trainer List (INACTIVE) --}}
                <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />

                {{-- Nav-Item Equipment (INACTIVE) --}}
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            {{-- User Profile Section --}}
            <div class="absolute bottom-0 w-64 p-4  flex justify-between bg-white border-t">
                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ ($user->image_url ?? auth()->user()->image_url) ?? asset('images/default-user.png') }}" alt="{{ $user->name ?? auth()->user()->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ $user->name ?? auth()->user()->name }}</p>
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

        {{-- Area Konten Utama (Main Content) --}}
        <div class="flex-1 ml-64">
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">

                {{-- Header Halaman --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">My Bookings</h2>
                    {{-- Tombol New Booking --}}
                    <a href="{{ route('customer.bookings.create') }}" {{-- Pastikan link ke create --}}
                       class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-plus w-4 h-4"></i>
                        <span>New Booking</span>
                    </a>
                </div>

                {{-- Notifikasi Sukses --}}
                 @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                 {{-- Notifikasi Error (jika ada) --}}
                 @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Daftar Booking --}}
                <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-sm text-gray-500 border-b bg-gray-50">
                                    <th class="px-6 py-3 font-medium">Trainer</th>
                                    <th class="px-6 py-3 font-medium">Date</th>
                                    <th class="px-6 py-3 font-medium">Time</th>
                                    <th class="px-6 py-3 font-medium">Duration</th>
                                    <th class="px-6 py-3 font-medium">Booked On</th>
                                    <th class="px-6 py-3 font-medium">Actions</th> {{-- === TAMBAHKAN KOLOM ACTIONS === --}}
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($bookings as $booking)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        {{-- Kolom Trainer --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <img
                                                    src="{{ $booking->trainer->image_url ?? asset('images/default-user.png') }}"
                                                    alt="{{ $booking->trainer->name }}"
                                                    class="w-8 h-8 object-cover rounded-full border"
                                                >
                                                <p class="font-medium text-gray-900">{{ $booking->trainer->name }}</p>
                                            </div>
                                        </td>
                                        {{-- Kolom Date --}}
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                                        </td>
                                         {{-- Kolom Time --}}
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ \Carbon\Carbon::parse($booking->time)->format('H:i') }}
                                        </td>
                                         {{-- Kolom Duration --}}
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $booking->duration }} mins
                                        </td>
                                        {{-- Kolom Booked On --}}
                                        <td class="px-6 py-4 text-gray-500 text-sm">
                                            {{-- Fix variable typo --}}
                                            {{ $booking->created_at ? $booking->created_at->format('d M Y, H:i') : 'N/A' }}
                                        </td>
                                        {{-- === TAMBAHKAN TOMBOL DELETE === --}}
                                        <td class="px-6 py-4">
                                            <form action="{{ route('customer.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 p-1 rounded-lg hover:bg-red-50 transition-colors duration-200" title="Delete Booking">
                                                    <i class="fas fa-trash-alt w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </td>
                                        {{-- ============================ --}}
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- Sesuaikan colspan menjadi 6 --}}
                                        <td colspan="6" class="py-10 text-center text-gray-500">
                                            You haven't made any bookings yet.
                                            <a href="{{ route('customer.bookings.create') }}" class="text-blue-600 hover:underline ml-1">Book your first session!</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination (jika diperlukan di Controller) --}}
                @if ($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->hasPages())
                    <div class="mt-6">
                         <x-pagination-footer
                            :firstItem="$bookings->firstItem()"
                            :lastItem="$bookings->lastItem()"
                            :total="$bookings->total()"
                            :onFirstPage="$bookings->onFirstPage()"
                            :hasMorePages="$bookings->hasMorePages()"
                            :previousPageUrl="$bookings->previousPageUrl()"
                            :nextPageUrl="$bookings->nextPageUrl()"
                            :lastPage="$bookings->lastPage()"
                            :currentPage="$bookings->currentPage()"
                            :pageUrl="fn($page) => $bookings->url($page)"
                        />
                    </div>
                 @endif

            </main>
        </div>
    </div>
</body>
</html>