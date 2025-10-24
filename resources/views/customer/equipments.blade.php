<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Equipment List | Customer</title>
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
                
                {{-- Nav-Item untuk Home/Dashboard Customer --}}
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />
                
                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                {{-- Nav-Item untuk Progress Tracking --}}
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
                
                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
                {{-- Nav-Item Equipment (ACTIVE) --}}
                <x-nav-item text="Equipment List" color="text-zinc-700" src="equipment.svg" location="customer.equipments.index" style="bg-blue-50 border-r-4 border-blue-500" />
                
            </nav>
            
            <div class="absolute bottom-0 w-64 p-4  flex justify-between bg-white">
                <div class="flex items-center">
                    <a href={{ route('profile.edit') }}>
                        <img class="w-8 h-8 rounded-full" src="{{ $user->image_url ?? asset('images/default-user.png') }}" alt="{{ $user->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Customer</p>
                    </div>
                </div>
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                                <img src="{{ asset('icons/logout.svg') }}" alt="logout">
                            </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex-1 ml-64">

            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">
                
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Gym Equipment List</h2>

                <div class="flex justify-end items-center mb-4">
                    {{-- Search Bar --}}
                    <x-search-bar route="customer.equipments.index" /> 
                </div>

                <div class="bg-white rounded-xl border shadow-sm">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-sm text-gray-500 border-b">
                                        <th class="pb-3 font-medium">Equipment</th> {{-- Kolom Nama & Gambar --}}
                                        <th class="pb-3 font-medium">Brand</th>
                                        <th class="pb-3 font-medium">Condition</th>
                                        <th class="pb-3 font-medium">Last Maintained</th>
                                        <th class="pb-3 font-medium">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @if($equipments->isEmpty())
                                        <tr>
                                            <td colspan="5" class="py-6 text-center text-gray-500">No equipment found or list is empty.</td>
                                        </tr>
                                    @else
                                        @foreach($equipments as $equipment)
                                            @php
                                                // Logika penentuan warna berdasarkan kondisi
                                                $condition = strtolower($equipment->condition);
                                                $conditionClass = match($condition) {
                                                    'baik', 'good', 'baru' => 'bg-green-100 text-green-800', 
                                                    'fair' => 'bg-yellow-100 text-yellow-800',
                                                    default => 'bg-red-100 text-red-800',
                                                };
                                                $conditionText = ucfirst($condition);
                                            @endphp
                                            <tr class="hover:bg-blue-50 transition-colors duration-200">
                                                
                                                {{-- KOLOM GAMBAR & NAMA --}}
                                                <td class="py-4 font-medium text-gray-900">
                                                    <div class="flex items-center space-x-3">
                                                        {{-- Placeholder Gambar (Menggunakan URL dari seeder/database atau placeholder default) --}}
                                                        <img 
                                                            src="{{ $equipment->image_url ?? asset('images/gym-equipment.jpeg') }}" 
                                                            alt="{{ $equipment->name }}"
                                                            class="w-12 h-12 object-cover rounded-md border"
                                                        >
                                                        <p>{{ $equipment->equipment_name }}</p>
                                                    </div>
                                                </td>

                                                {{-- KOLOM BRAND --}}
                                                <td class="py-4 text-gray-600">{{ $equipment->brand }}</td>
                                                
                                                {{-- KOLOM CONDITION --}}
                                                <td class="py-4">
                                                    <span class="px-2 py-1 text-xs font-medium {{ $conditionClass }} rounded-full">
                                                        {{ $conditionText }}
                                                    </span>
                                                </td>
                                                
                                                {{-- KOLOM LAST MAINTAINED --}}
                                                <td class="py-4 text-gray-600">
                                                    {{ $equipment->last_maintenance ? \Carbon\Carbon::parse($equipment->last_maintenance)->format('d M Y') : 'N/A' }}
                                                </td>
                                                
                                                {{-- KOLOM DESCRIPTION --}}
                                                <td class="py-4 text-sm text-gray-500 max-w-xs overflow-hidden truncate whitespace-normal">
                                                    {{ $equipment->description ?? 'No description available.' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if($equipments->hasPages())
                    <x-pagination-footer
                        firstItem="{{ $equipments->firstItem() }}"
                        lastItem="{{ $equipments->lastItem() }}"
                        total="{{ $equipments->total() }}"
                        onFirstPage="{{ $equipments->onFirstPage() }}"
                        hasMorePages="{{ $equipments->hasMorePages() }}"
                        previousPageUrl="{{ $equipments->previousPageUrl() }}"
                        nextPageUrl="{{ $equipments->nextPageUrl() }}"
                        lastPage="{{ $equipments->lastPage() }}"
                        currentPage="{{ $equipments->currentPage() }}"
                        model="equipments"
                    />
                @endif
            </main>
        </div>
    </div>
</body>
</html>