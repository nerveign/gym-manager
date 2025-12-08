<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment List | Trainer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen">

        {{-- ================= SIDEBAR TRAINER ================= --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r flex flex-col">

            {{-- Header Sidebar --}}
            <x-dashboard-header name="{{ auth()->user()->name }}" />

            {{-- Navigation Menu --}}
            <nav class="mt-6 flex-1 overflow-y-auto">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

                {{-- Dashboard --}}
                <a href="{{ route('trainer.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                    <img src="{{ asset('icons/home.svg') }}" alt="home" class="w-5 h-5 mr-3 text-gray-500">
                    <span class="font-medium">Dashboard</span>
                </a>

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-4">Aktivitas Saya</div>

                {{-- Bookings --}}
                <a href="{{ route('trainer.bookings') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                    <img src="{{ asset('icons/calendar.svg') }}" alt="booking" class="w-5 h-5 mr-3 text-gray-500">
                    <span>My Bookings</span>
                </a>

                {{-- Classes --}}
                <a href="{{ route('trainer.classes') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                    <img src="{{ asset('icons/class.svg') }}" alt="class" class="w-5 h-5 mr-3 text-gray-500">
                    <span>My Classes</span>
                </a>

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Fasilitas</div>

                {{-- Equipment List (ACTIVE STATE) --}}
                <a href="{{ route('trainer.equipments.index') }}" class="flex items-center px-4 py-3 bg-blue-50 text-blue-600 border-r-4 border-blue-600 transition-colors">
                    <img src="{{ asset('icons/equipment.svg') }}" alt="equipment" class="w-5 h-5 mr-3 text-blue-600">
                    <span class="font-medium">Equipment List</span>
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
                
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Equipment Management</h2>
                </div>

                {{-- Search Bar --}}
                <div class="mb-6">
                    <form method="GET" action="{{ route('trainer.equipments.index') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search equipment by name or description..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            Search
                        </button>
                        @if(request('search'))
                        <a href="{{ route('trainer.equipments.index') }}" class="px-6 py-3 bg-white text-gray-700 font-medium rounded-lg hover:bg-gray-50 border border-gray-300 transition-colors shadow-sm flex items-center">
                            Clear
                        </a>
                        @endif
                    </form>
                </div>

                {{-- Tabel Equipment (Desain User) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-white border-b border-gray-100 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    <th class="px-6 py-4">Equipment</th>
                                    <th class="px-6 py-4">Brand</th>
                                    <th class="px-6 py-4">Condition</th>
                                    <th class="px-6 py-4">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($equipments as $equipment)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    {{-- Kolom Equipment: Link Wrapper --}}
                                    <td class="px-6 py-4">
                                        {{-- Link langsung ke Detail --}}
                                        <a href="{{ route('trainer.equipments.show', $equipment->id) }}" class="flex items-center group cursor-pointer">
                                            {{-- Image --}}
                                            @if($equipment->image_url)
                                            <div class="w-12 h-12 rounded-lg overflow-hidden mr-4 border border-gray-100 flex-shrink-0 bg-white">
                                                <img class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" 
                                                     src="{{ $equipment->image_url }}" 
                                                     alt="{{ $equipment->equipment_name }}">
                                            </div>
                                            @else
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4 border border-gray-200 flex-shrink-0">
                                                <i class="fas fa-dumbbell text-gray-400"></i>
                                            </div>
                                            @endif
                                            
                                            {{-- Text Info --}}
                                            <div>
                                                <p class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors text-sm">
                                                    {{ $equipment->equipment_name }}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">
                                                    {{ Str::limit($equipment->description, 60) }}
                                                </p>
                                            </div>
                                        </a>
                                    </td>

                                    {{-- Kolom Brand --}}
                                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                        {{ $equipment->brand ?? '-' }}
                                    </td>

                                    {{-- Kolom Condition --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $conditionClass = match(strtolower($equipment->condition)) {
                                                'baik', 'good', 'baru' => 'bg-green-50 text-green-700 border border-green-100',
                                                'rusak', 'broken' => 'bg-red-50 text-red-700 border border-red-100',
                                                'maintenance' => 'bg-yellow-50 text-yellow-700 border border-yellow-100',
                                                default => 'bg-gray-50 text-gray-700 border border-gray-100'
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-md {{ $conditionClass }}">
                                            {{ ucfirst($equipment->condition) }}
                                        </span>
                                    </td>

                                    {{-- Kolom Last Updated --}}
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $equipment->updated_at ? $equipment->updated_at->diffForHumans() : 'No Record' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-search text-gray-300 text-2xl"></i>
                                            </div>
                                            <p class="font-medium">No equipment found.</p>
                                            @if(request('search'))
                                            <p class="text-sm mt-1">Try adjusting your search terms.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $equipments->withQueryString()->links() }}
                </div>

            </main>
        </div>
    </div>
</body>

</html>