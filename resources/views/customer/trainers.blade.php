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
        {{-- Fixed Sidebar --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

                {{-- Nav-Item untuk Home --}}
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                {{-- Nav-Item untuk Progress Tracking --}}
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />

                {{-- Nav-Item untuk My Bookings --}}
                <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>

                {{-- Nav-Item Trainer List (ACTIVE) --}}
                <x-nav-item text="Trainer List" color="text-zinc-700" src="user.svg" location="customer.trainers.index" style="bg-blue-50 border-r-4 border-blue-500" />

                {{-- Nav-Item Equipment --}}
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            {{-- User Profile Section --}}
            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        @if($user->image_url)
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ $user->image_url }}" alt="{{ $user->name }}">
                        @else
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-gray-400 text-sm"></i>
                        </div>
                        @endif
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Customer</p>
                    </div>
                </div>
                <div>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 mr-1">
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div class="flex-1 ml-64">

            {{-- Scrollable Content --}}
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Trainers Management</h2>
                
                <x-search-bar 
                    action="{{ route('customer.trainers.index') }}" 
                    placeholder="Search trainers by name, email, or phone..."
                />

                {{-- All Trainers Table --}}
                <div class="bg-white rounded-xl border">
                    <div class="px-6 py-3 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">All Trainers</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-sm text-gray-500 border-b">
                                        <th class="pb-3 font-medium">Trainers</th>
                                        <th class="pb-3 font-medium">Join Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($trainers as $trainer)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="py-4">
                                            <div class="flex items-center">
                                                @if($trainer->image_url)
                                                <img class="w-8 h-8 rounded-full object-cover mr-3" src="{{ $trainer->image_url }}" alt="{{ $trainer->name }}">
                                                @else
                                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-gray-400 text-sm"></i>
                                                </div>
                                                @endif
                                                {{-- === PERUBAHAN DI SINI === --}}
                                                <div>
                                                    <a href="{{ route('customer.trainers.show', $trainer->id) }}" class="font-medium text-gray-900 hover:text-blue-600 transition-colors">{{ $trainer->name }}</a>
                                                    <p class="text-sm text-gray-500">{{ $trainer->email }}</p>
                                                </div>
                                                {{-- ======================== --}}
                                            </div>
                                        </td>
                                        <td class="py-4 text-gray-600">{{ $trainer->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="py-10 text-center text-gray-500">No trainers found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
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
                    model="trainers"
                />

            </main>
        </div>
    </div>
</body>
</html>
