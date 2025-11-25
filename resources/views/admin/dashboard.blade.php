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
                
                <x-nav-item text="Dashboard" color="text-zinc-700" src="home.svg" location="admin.dashboard" style="bg-blue-50 border-r-4 border-blue-500" />
                
                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Manajemen Data</div>                
                <x-nav-item text="Users" color="text-zinc-700" src="users.svg" location="admin.users_management" />
                <x-nav-item text="Trainers" color="text-gray-600" src="user.svg" location="admin.trainers_management" />
                <x-nav-item text="Bookings" color="text-gray-600" src="calendar.svg" location="admin.bookings_management" />
                <x-nav-item text="Classes" color="text-gray-600" src="class.svg" location="admin.classes_management" />
                <x-nav-item text="Equipment" color="text-gray-600" src="equipment.svg" location="admin.equipments_management" />
                <x-nav-item text="Transactions" color="text-gray-600" src="dollar-sign.svg" location="admin.transactions_management" />
            </nav>
            
            <!-- User Profile Section -->
            <div class="absolute bottom-0 w-64 p-4  flex justify-between bg-white">
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
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 mt-2">
                    <!-- Monthly Revenue -->
                    <div class="bg-white rounded-xl p-6 border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                                <p class="text-2xl font-semibold text-gray-900 mt-1">Rp {{ number_format($revenue['monthly'], 0, ',', '.') }}</p>
                            </div>
                            <div class="size-12 flex items-center justify-center bg-purple-100 rounded-lg">
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
                            <div class="size-12 flex items-center justify-center bg-blue-100 rounded-lg">
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
                            <div class="size-12 flex items-center justify-center bg-green-100 rounded-lg">
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
                            <div class="size-12 flex items-center justify-center bg-orange-100 rounded-lg">
                                <i class="fas fa-dumbbell text-orange-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Tables Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                    <!-- Recent Trainers -->
                    <div class="bg-white rounded-xl border">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Trainers</h3>
                            <a href="{{ route('admin.trainers_management') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                                View All
                            </a>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($recentTrainers as $trainer)
                                    <div class="flex items-center space-x-4">
                                        @if($trainer->image_url)
                                            <img class="w-10 h-10 rounded-full object-cover flex-shrink-0" src="{{ $trainer->image_url }}" alt="{{ $trainer->name }}">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $trainer->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $trainer->email }}</p>
                                        </div>
                                        <span class="text-xs text-gray-400 flex-shrink-0">{{ $trainer->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
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
                                    <p class="text-sm text-gray-600">Total Revenue</p>
                                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($revenue['total'] / 1000000, 1) }}M</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions Table -->
                <div class="bg-white rounded-xl border">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                        <a href="{{ route('admin.transactions_management') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                            View All
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-sm text-gray-500 border-b">
                                        <th class="pb-3 font-medium">Transaction ID</th>
                                        <th class="pb-3 font-medium">User</th>
                                        <th class="pb-3 font-medium">Amount</th>
                                        <th class="pb-3 font-medium">Status</th>
                                        <th class="pb-3 font-medium">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($recentTransactions as $transaction)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="py-4">
                                                <p class="font-medium text-gray-900">#{{ $transaction->id }}</p>
                                                <p class="text-sm text-gray-500">{{ $transaction->order_id ?? 'N/A' }}</p>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    @if($transaction->membership && $transaction->membership->user && $transaction->membership->user->image_url)
                                                        <img class="w-8 h-8 rounded-full object-cover mr-3" src="{{ $transaction->membership->user->image_url }}" alt="{{ $transaction->membership->user->name }}">
                                                    @else
                                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                            <i class="fas fa-user text-gray-400 text-sm"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $transaction->membership->user->name ?? 'Unknown User' }}</p>
                                                        <p class="text-sm text-gray-500">{{ $transaction->membership->user->email ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-gray-600 font-medium">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                            <td class="py-4">
                                                @php
                                                    $statusColors = [
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'success' => 'bg-green-100 text-green-800',
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'failed' => 'bg-red-100 text-red-800',
                                                        'cancelled' => 'bg-gray-100 text-gray-800'
                                                    ];
                                                    $statusLabels = [
                                                        'completed' => 'Completed',
                                                        'success' => 'Completed',
                                                        'pending' => 'Pending',
                                                        'failed' => 'Failed',
                                                        'cancelled' => 'Cancelled'
                                                    ];
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 text-gray-600">{{ $transaction->created_at->format('d M Y') }}</td>
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
