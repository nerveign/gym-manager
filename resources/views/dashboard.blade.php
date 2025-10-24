<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard | FitAja</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name }}" />
            
            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                
                {{-- Nav-Item untuk Home/Dashboard Customer --}}
                <x-nav-item text="Home" color="text-zinc-700" src="home.svg" location="customer.dashboard" style="bg-blue-50 border-r-4 border-blue-500" />
                
                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                {{-- Nav-Item untuk Progress Tracking --}}
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
                
                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
                {{-- Nav-Item untuk Equipment List (LINK BARU) --}}
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />

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
                
                {{-- Header Utama --}}
                <div class="p-2">
                     <h2 class="text-xl font-semibold text-gray-900">Customer Dashboard</h2>
                </div>

                {{-- Info Selamat Datang (Welcome Card) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Welcome, {{ auth()->user()->name }}! 👋</h3>
                        <p class="text-gray-600">Manage your gym activities and track your progress.</p>
                    </div>
                </div>

                {{-- STATS CARD BARU --}}
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 mb-4">
                    
                    {{-- Membership Status (MEMANGGIL PARTIAL) --}}
                    @include('customer.partials.membership-card')

                    {{-- Enrolled Classes --}}
                    <div class="bg-white rounded-xl p-6 border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Enrolled Classes</p>
                                <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $enrolledClasses }}</p>
                            </div>
                            <div class="size-12 flex items-center justify-center bg-blue-100 rounded-lg">
                                <i class="fas fa-dumbbell text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Upcoming Bookings --}}
                    <div class="bg-white rounded-xl p-6 border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Upcoming Bookings</p>
                                <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $upcomingBookings }}</p>
                            </div>
                            <div class="size-12 flex items-center justify-center bg-purple-100 rounded-lg">
                                <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Progress Section --}}
                <div class="bg-white rounded-xl border mt-6">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Progress</h3>
                        <a href="{{ route('customer.progress.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                            View All Progress
                        </a>
                    </div>
                    <div class="p-6">
                        @if($recentProgress->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentProgress as $progress)
                                    <div class="flex justify-between items-start border-b pb-4 last:border-b-0 last:pb-0">
                                        <div class="flex-1 min-w-0 pr-4">
                                            <p class="font-medium text-gray-900">{{ $progress->exercise }}</p>
                                            <p class="text-sm text-gray-600">{{ $progress->duration }} minutes</p>
                                            <p class="text-sm text-gray-500 truncate">{{ $progress->description }}</p>
                                        </div>
                                        <div class="flex gap-1 items-center flex-shrink-0">
                                            <a href="{{ route('customer.progress.edit', $progress) }}" 
                                               class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200" title="Edit">
                                                <i class="fas fa-edit w-4 h-4"></i>
                                            </a>
                                            <form action="{{ route('customer.progress.destroy', $progress) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to delete this progress record?')"
                                                        class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200" title="Delete">
                                                    <i class="fas fa-trash-alt w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500">
                                <p>No recent progress recorded. Start your tracking now!</p>
                                <a href="{{ route('customer.progress.create') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800 font-medium">
                                    Add Your First Progress
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>