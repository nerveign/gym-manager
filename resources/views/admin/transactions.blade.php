<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions Management</title>
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
                
                <x-nav-item text="Users" color="text-gray-600" src="users.svg" location="admin.users_management" />
                <x-nav-item text="Trainers" color="text-gray-600" src="user.svg" location="admin.trainers_management" />
                <x-nav-item text="Bookings" color="text-gray-600" src="calendar.svg" location="admin.bookings_management" />
                <x-nav-item text="Classes" color="text-gray-600" src="class.svg" location="admin.classes_management" />
                <x-nav-item text="Equipment" color="text-gray-600" src="equipment.svg" location="admin.equipments_management" />
                <x-nav-item text="Transactions" color="text-blue-600" src="dollar-sign.svg" location="admin.transactions_management" style="bg-blue-50 border-r-4 border-blue-500" />
            </nav>
            
            <!-- User Profile Section -->
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
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Transactions Management</h2>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-300 to-blue-500 rounded-md flex items-center justify-center">
                                        <i class="fas fa-receipt text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Transaksi</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total'] ?? 0) }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                        <i class="fas fa-check-circle text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['completed'] ?? 0) }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['pending'] ?? 0) }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-300 to-blue-500 rounded-md flex items-center justify-center">
                                        <i class="fas fa-dollar-sign text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Pendapatan</dt>
                                        <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($stats['total_amount'] ?? 0) }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Filter Transaksi</h3>
                    </div>
                    <div class="px-6 py-4">
                        <form method="GET" action="{{ route('admin.transactions_management') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Cari Transaksi</label>
                                <input type="text" 
                                       id="search"
                                       name="search" 
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       placeholder="Payment ID atau Nama Customer"
                                       value="{{ request('search') }}">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" 
                                        name="status" 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">All Status</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                <select id="payment_method" 
                                        name="payment_method" 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">Semua Metode</option>
                                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="gopay" {{ request('payment_method') == 'gopay' ? 'selected' : '' }}>GoPay</option>
                                    <option value="ovo" {{ request('payment_method') == 'ovo' ? 'selected' : '' }}>OVO</option>
                                    <option value="dana" {{ request('payment_method') == 'dana' ? 'selected' : '' }}>DANA</option>
                                </select>
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-search mr-2"></i>Filter
                                </button>
                                <a href="{{ route('admin.transactions_management') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-times mr-2"></i>Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Transaksi</h3>
                    </div>
                    <div class="overflow-hidden">
                        @if(isset($transactions) && $transactions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($transactions as $transaction)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->payment_gateway_id }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($transaction->membership && $transaction->membership->user)
                                                        @if($transaction->membership->user->image_url)
                                                            <img class="h-8 w-8 rounded-full object-cover" src="{{ $transaction->membership->user->image_url }}" alt="">
                                                        @else
                                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                                <i class="fas fa-user text-gray-400 text-sm"></i>
                                                            </div>
                                                        @endif
                                                        <div class="ml-3">
                                                            <div class="text-sm font-medium text-gray-900">{{ $transaction->membership->user->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $transaction->membership->user->email }}</div>
                                                        </div>
                                                    @else
                                                        <span class="text-sm text-gray-500">User Deleted</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($transaction->amount) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ in_array($transaction->payment_method, ['gopay', 'ovo', 'dana']) ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'success' => 'bg-green-100 text-green-800',
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'failed' => 'bg-red-100 text-red-800'
                                                    ];
                                                    $statusLabels = [
                                                        'completed' => 'Completed',
                                                        'success' => 'Completed',
                                                        'pending' => 'Pending',
                                                        'failed' => 'Failed'
                                                    ];
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $statusLabels[$transaction->status] ?? $transaction->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
                                                <div class="text-sm text-gray-500">{{ $transaction->created_at->diffForHumans() }}</div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            @if($transactions->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200">
                                {{ $transactions->links() }}
                            </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-receipt text-gray-400 text-5xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900">Tidak ada transaksi</h3>
                                <p class="text-gray-500">Belum ada transaksi yang tercatat dalam sistem.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
