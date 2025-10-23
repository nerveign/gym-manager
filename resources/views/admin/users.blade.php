<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                
                <x-nav-item text="Home" color="text-zinc-700" src="home.svg" location="admin.dashboard" />


                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Management</div>
                
                <x-nav-item text="Users" color="text-zinc-700" src="users.svg" location="admin.users_management"  style="bg-blue-50 border-r-4 border-blue-500" />
                <x-nav-item text="Trainer" color="text-gray-600" src="user.svg" location="admin.trainers_management" />
                <x-nav-item text="Booking" color="text-gray-600" src="calendar.svg" location="admin.bookings_management" />
                <x-nav-item text="Class" color="text-gray-600" src="class.svg" location="admin.classes_management" />
                <x-nav-item text="Equipment" color="text-gray-600" src="equipment.svg" location="admin.equipments_management" />
            </nav>
            
            <!-- User Profile Section -->
            <div class="absolute bottom-0 w-64 p-4  flex justify-between bg-white">
                <div class="flex items-center">
                    <a href={{ route('profile.edit') }}>
                        <img class="w-8 h-8 rounded-full" src="{{ $user->image_url }}" alt="{{ $user->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
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

        <!-- Main Content Area -->
        <div class="flex-1 ml-64">

            <!-- Scrollable Content -->
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">
                <!-- Recent Members Table -->
                <div class="bg-white rounded-xl border">
                    <div class="px-6 py-3 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">All Users</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-sm text-gray-500 border-b">
                                        <th class="pb-3 font-medium">User</th>
                                        <th class="pb-3 font-medium">Join Date</th>
                                        <th class="pb-3 font-medium">Member Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                        @foreach($customers as $customer)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    <img class="w-8 h-8 rounded-full mr-3" src="{{ $customer->image_url }}" alt="John Doe">
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                                        <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-gray-600">{{ $customer->membership->created_at->format('d M Y') }}</td>
                                            <td class="py-4">
                                                    @if($customer->membership)
                                                        @if($customer->membership->status == 'active')
                                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                                Active
                                                            </span>
                                                        @else
                                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                                Inactive
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                                            No Membership
                                                        </span>
                                                    @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Pagination -->
                @if($customers->total() > 0)
                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} results
                    </div>
                    <div class="flex space-x-2">
                        <!-- Previous Page -->
                        @if($customers->onFirstPage())
                            <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $customers->previousPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        @foreach(range(1, $customers->lastPage()) as $page)
                            @if($page == $customers->currentPage())
                                <span class="px-3 py-1 bg-blue-600 text-white rounded-lg">{{ $page }}</span>
                            @else
                                <a href="{{ $customers->url($page) }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        <!-- Next Page -->
                        @if($customers->hasMorePages())
                            <a href="{{ $customers->nextPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </main>
        </div>
    </div>
</body>
</html>
