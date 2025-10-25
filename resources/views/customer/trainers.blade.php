<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer List | Customer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- Fixed Sidebar (Sidebar) --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

                {{-- Nav-Item untuk Home (INACTIVE) --}}
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                {{-- Nav-Item untuk Progress Tracking (INACTIVE) --}}
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />

                {{-- === PENAMBAHAN DI SINI === --}}
                {{-- Nav-Item untuk My Bookings (INACTIVE) --}}
                {{-- Arahkan ke dashboard dulu karena rute booking customer belum ada --}}
                <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />
                {{-- ======================== --}}

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>

                {{-- Nav-Item Trainer List (ACTIVE) --}}
                <x-nav-item text="Trainer List" color="text-zinc-700" src="user.svg" location="customer.trainers.index" style="bg-blue-50 border-r-4 border-blue-500" />

                {{-- Nav-Item Equipment (INACTIVE) --}}
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            {{-- User Profile Section --}}
            <div class="absolute bottom-0 w-64 p-4  flex justify-between bg-white border-t"> {{-- Tambah border-t --}}
                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}"> {{-- Perbaiki href --}}
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
                            <button type="submit" class="flex items-center p-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200"> {{-- Ubah padding --}}
                                <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 text-gray-500"> {{-- Tambah class --}}
                            </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Area Konten Utama (Main Content) --}}
        <div class="flex-1 ml-64">
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">

                {{-- Search Bar --}}
                <div class="mb-4">
                    <x-search-bar :route="route('customer.trainers.index')" placeholder="Search trainers by name or email..." />
                </div>

                <h2 class="text-lg font-semibold text-gray-900 mb-4">Our Trainers</h2>

                {{-- Tabel Trainer (Diadaptasi dari admin/trainers.blade.php) --}}
                <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-sm text-gray-500 border-b bg-gray-50">
                                    <th class="px-6 py-3 font-medium">Trainer</th>
                                    <th class="px-6 py-3 font-medium">Email</th>
                                    <th class="px-6 py-3 font-medium">Join Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($trainers as $trainer)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            <div class="flex items-center space-x-3">
                                                <img
                                                    src="{{ $trainer->image_url ?? asset('images/default-user.png') }}"
                                                    alt="{{ $trainer->name }}"
                                                    class="w-10 h-10 object-cover rounded-full border"
                                                >
                                                <p>{{ $trainer->name }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $trainer->email }}</td>
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $trainer->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-10 text-center text-gray-500">No trainers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if($trainers->hasPages())
                    <div class="mt-6">
                        <x-pagination-footer
                            firstItem="{{ $trainers->firstItem() }}"
                            lastItem="{{ $trainers->lastItem() }}"
                            total="{{ $trainers->total() }}"
                            onFirstPage="{{ $trainers->onFirstPage() }}"
                            hasMorePages="{{ $trainers->hasMorePages() }}"
                            previousPageUrl="{{ $trainers->previousPageUrl() }}"
                            nextPageUrl="{{ $trainers->nextPageUrl() }}"
                            lastPage="{{ $trainers->lastPage() }}"
                            currentPage="{{ $trainers->currentPage() }}"
                            :pageUrl="fn($page) => request()->fullUrlWithQuery(['page' => $page])"
                        />
                    </div>
                @endif
            </main>
        </div>
    </div>
</body>
</html>