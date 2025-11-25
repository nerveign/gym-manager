<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Equipment</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Fixed Sidebar -->
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name }}" />
            
            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                
                <x-nav-item text="Dashboard" color="text-zinc-700" src="home.svg" location="admin.dashboard" />


                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Manajemen Data</div>
                
                <x-nav-item text="Users" color="text-zinc-700" src="users.svg" location="admin.users_management"  />
                <x-nav-item text="Trainers" color="text-gray-600" src="user.svg" location="admin.trainers_management" />
                <x-nav-item text="Bookings" color="text-gray-600" src="calendar.svg" location="admin.bookings_management" />
                <x-nav-item text="Classes" color="text-gray-600" src="class.svg" location="admin.classes_management" />
                <x-nav-item text="Equipment" color="text-gray-600" src="equipment.svg" location="admin.equipments_management" style="bg-blue-50 border-r-4 border-blue-500"  />
                <x-nav-item text="Transactions" color="text-gray-600" src="dollar-sign.svg" location="admin.transactions_management" />
            </nav>
            
            <!-- User Profile Section -->
            <div class="absolute bottom-0 w-64 p-4  flex justify-between bg-white">
                <div class="flex items-center">
                    <a href={{ route('profile.edit') }}>
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
                        <p class="text-xs text-gray-500">Administrator</p>
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

        <!-- Main Content Area -->
        <div class="flex-1 ml-64">

            <!-- Scrollable Content -->
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Equipment Management</h2>
                </div>

                <!-- Search Bar -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('admin.equipments_management') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search equipment by name or description..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.equipments_management') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Equipment Table -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 font-medium text-gray-700">Equipment</th>
                                        <th class="text-left py-3 font-medium text-gray-700">Brand</th>
                                        <th class="text-left py-3 font-medium text-gray-700">Condition</th>
                                        <th class="text-left py-3 font-medium text-gray-700">Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($equipments as $equipment)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    @if($equipment->image_url)
                                                        <img class="w-12 h-12 rounded-lg object-cover mr-4" src="{{ $equipment->image_url }}" alt="{{ $equipment->equipment_name }}">
                                                    @else
                                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                                            <i class="fas fa-dumbbell text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $equipment->equipment_name }}</p>
                                                        <p class="text-sm text-gray-500">{{ Str::limit($equipment->description, 50) }}</p>
                                                        <p class="text-xs text-gray-400">{{ $equipment->brand }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-gray-600">{{ $equipment->brand ?? 'General' }}</td>
                                            <td class="py-4">
                                                @if($equipment->condition == 'Baik' || $equipment->condition == 'Baru')
                                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                        {{ $equipment->condition }}
                                                    </span>
                                                @elseif($equipment->condition == 'Rusak')
                                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                        {{ $equipment->condition }}
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                        {{ $equipment->condition }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 text-gray-600">
                                                {{ $equipment->updated_at ? $equipment->updated_at->format('d M Y') : 'No Record' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-8 text-center text-gray-500">
                                                @if(request('search'))
                                                    No equipment found matching "{{ request('search') }}"
                                                @else
                                                    No equipment available
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $equipments->links() }}
                </div>

            </main>
        </div>
    </div>
</body>
</html>
