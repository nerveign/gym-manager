<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Detail | {{ $customer->name }}</title>
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
                
                <x-nav-item text="Users" color="text-blue-600" src="users.svg" location="admin.users_management" style="bg-blue-50 border-r-4 border-blue-500" />
                <x-nav-item text="Trainers" color="text-gray-600" src="user.svg" location="admin.trainers_management" />
                <x-nav-item text="Bookings" color="text-gray-600" src="calendar.svg" location="admin.bookings_management" />
                <x-nav-item text="Classes" color="text-gray-600" src="class.svg" location="admin.classes_management" />
                <x-nav-item text="Equipment" color="text-gray-600" src="equipment.svg" location="admin.equipments_management" />
                <x-nav-item text="Transactions" color="text-gray-600" src="dollar-sign.svg" location="admin.transactions_management" />
            </nav>
            
            <!-- User Profile Section -->
            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <div class="flex items-center">
                    @if($user->image_url)
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ $user->image_url }}" alt="{{ $user->name }}">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-gray-400 text-sm"></i>
                        </div>
                    @endif
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
                <!-- Back Navigation -->
                <div class="mb-6">
                    <a href="{{ route('admin.users_management') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Back to Users</span>
                    </a>
                </div>

                <!-- User Profile Header -->
                <div class="w-full bg-white rounded-xl shadow-lg border relative overflow-hidden mb-6">
                    
                    <!-- Banner -->
                    <div class="relative overflow-hidden rounded-t-xl h-36" style="background: #dbeafe;">
                    </div>

                    <!-- Profile Card Content -->
                    <div class="relative flex flex-col md:flex-row p-6 pt-0">
                        
                        <!-- Left Column (Profile Summary) -->
                        <div class="w-full md:w-1/3 text-center md:text-left -mt-16 md:-mt-12 md:pr-6">
                            
                            <div class="flex flex-col items-center md:items-start space-y-4">
                                @if($customer->image_url)
                                    <img class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover" src="{{ $customer->image_url }}" alt="{{ $customer->name }}">
                                @else
                                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                                
                                <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $customer->name }}</h1>
                                <p class="text-sm text-gray-500">Customer</p>
                                
                                <!-- Status Badge -->
                                @if($customer->membership && $customer->membership->status == 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1.5"></i>
                                        Active Member
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1.5"></i>
                                        Inactive Member
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Right Column (User Cards) -->
                        <div class="w-full md:w-2/3 md:pl-6 border-t md:border-t-0 md:border-l pt-8 md:pt-4 mt-6 md:mt-0">
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <!-- User Information Card -->
                                <div class="bg-white rounded-lg border p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">User Information</h3>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-sm text-gray-600">Email</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $customer->email }}</p>
                                        </div>
                                        @if($customer->phone)
                                            <div>
                                                <p class="text-sm text-gray-600">Phone</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $customer->phone }}</p>
                                            </div>
                                        @endif
                                        @if($customer->address)
                                            <div>
                                                <p class="text-sm text-gray-600">Address</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $customer->address }}</p>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm text-gray-600">Member since</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $customer->created_at->format('F Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Membership Status Card -->
                                <div class="bg-white rounded-lg border p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Membership Status</h3>
                                    <div class="space-y-3">
                                        @if($customer->membership)
                                            <div>
                                                <p class="text-sm text-gray-600">Status</p>
                                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($customer->membership->status) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Type</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $customer->membership->type ?? 'Regular' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Start Date</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $customer->membership->created_at->format('d M Y') }}</p>
                                            </div>
                                        @else
                                            <div>
                                                <p class="text-sm text-gray-600">Status</p>
                                                <p class="text-sm font-medium text-red-600">No Membership</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Transactions -->
                    <div class="bg-white rounded-xl border shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-credit-card mr-2 text-blue-500"></i>
                                Recent Transactions
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($transactions->count() > 0)
                                <div class="space-y-3">
                                    @foreach($transactions->take(5) as $transaction)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-dollar-sign text-blue-600 text-sm"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">Payment Transaction</p>
                                                    <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                                                @php
                                                    $statusLabels = [
                                                        'completed' => 'Completed',
                                                        'success' => 'Completed',
                                                        'pending' => 'Pending',
                                                        'failed' => 'Failed'
                                                    ];
                                                    $statusColors = [
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'success' => 'bg-green-100 text-green-800',
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'failed' => 'bg-red-100 text-red-800'
                                                    ];
                                                @endphp
                                                <span class="text-xs px-2 py-1 rounded-full {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-receipt text-4xl mb-3"></i>
                                    <p>No transactions found</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Bookings -->
                    <div class="bg-white rounded-xl border shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-green-500"></i>
                                Recent Bookings
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($recentBookings->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentBookings as $booking)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user-tie text-green-600 text-sm"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">{{ $booking->trainer->name ?? 'Personal Training' }}</p>
                                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }} - {{ $booking->time }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-gray-500">{{ $booking->duration }} minutes</p>
                                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                                    Scheduled
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-calendar-alt text-4xl mb-3"></i>
                                    <p>No bookings found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
