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
                
                <x-nav-item text="Home" color="text-zinc-700" src="home.svg" location="admin.dashboard" style="bg-blue-50 border-r-4 border-blue-500" />
                
                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Management</div>                
                <x-nav-item text="Users" color="text-zinc-700" src="users.svg" location="admin.users_management" />
                <x-nav-item text="Trainer" color="text-gray-600" src="user.svg" location="admin.trainers_management" />
                <x-nav-item text="Booking" color="text-gray-600" src="calendar.svg" location="admin.bookings_management" />
                <x-nav-item text="Class" color="text-gray-600" src="class.svg" location="admin.classes_management" />
                <x-nav-item text="Equipment" color="text-gray-600" src="equipment.svg" location="admin.equipments_management" />
                <x-nav-item text="Transaction" color="text-gray-600" src="dollar-sign.svg" location="admin.transactions_management" />
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
                <!-- Stats Cards -->
                <div class="p-2" >
                     <h2 class="text-xl font-semibold text-gray-900">Overview</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 mt-2">
                    <!-- Monthly Revenue -->
                    <div class="bg-white rounded-xl p-6 border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                                <p class="text-2xl font-semibold text-gray-900 mt-1">Rp. 1.450.000</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Members Card -->
                    <div class="bg-white rounded-xl p-6 border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Users</p>
                                <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $stats['customer_count'] }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Active Memberships -->
                    <div class="bg-white rounded-xl p-6 border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Active Memberships</p>
                                <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $stats['membership_count'] }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-lg">
                                <i class="fas fa-id-card text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Equipment Count -->
                    <div class="bg-white rounded-xl p-6 border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Equipment</p>
                                <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $stats['equipment_count'] }}</p>
                            </div>
                            <div class="p-3 bg-orange-100 rounded-lg">
                                <i class="fas fa-dumbbell text-orange-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Tables Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl border">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user-plus text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">New member registered</p>
                                        <p class="text-xs text-gray-500">John Doe joined the gym</p>
                                    </div>
                                    <span class="text-xs text-gray-400 flex-shrink-0">2 min ago</span>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-dumbbell text-blue-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">New class scheduled</p>
                                        <p class="text-xs text-gray-500">Yoga class by Trainer Sarah</p>
                                    </div>
                                    <span class="text-xs text-gray-400 flex-shrink-0">1 hour ago</span>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-credit-card text-purple-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Payment received</p>
                                        <p class="text-xs text-gray-500">Monthly membership - $99</p>
                                    </div>
                                    <span class="text-xs text-gray-400 flex-shrink-0">3 hours ago</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white rounded-xl border">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Quick Stats</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-4 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-chalkboard-teacher text-blue-600 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">Total Classes</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $stats['class_count'] }}</p>
                                </div>
                                <div class="text-center p-4 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-calendar-check text-green-600 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">Total Bookings</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $stats['booking_count'] }}</p>
                                </div>
                                <div class="text-center p-4 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-user-friends text-purple-600 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">Total Trainers</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $stats['trainer_count'] }}</p>
                                </div>
                                <div class="text-center p-4 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-chart-line text-orange-600 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">Occupancy Rate</p>
                                    <p class="text-xl font-bold text-gray-900">78%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Members Table -->
                <div class="bg-white rounded-xl border">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Members</h3>
                        <a href="{{ route('admin.users_management') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                            View All
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-sm text-gray-500 border-b">
                                        <th class="pb-3 font-medium">Member</th>
                                        <th class="pb-3 font-medium">Join Date</th>
                                        <th class="pb-3 font-medium">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($recentMemberships as $membership)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    <img class="w-8 h-8 rounded-full mr-3" src="{{ $membership->user_image }}" alt="John Doe">
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $membership->user_name  }}</p>
                                                        <p class="text-sm text-gray-500">{{  $membership->user_email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-gray-600"> {{ $membership->created_at->format('d M Y') }}</td>
                                            <td class="py-4">
                                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">{{ $membership->status }}</span>
                                            </td>
                                        </tr>
                                        @endforeach 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
              
            </main>
        </div>
    </div>
</body>
</html>
