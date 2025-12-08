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
        <x-trainer-sidebar activeMenu="equipment" />

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