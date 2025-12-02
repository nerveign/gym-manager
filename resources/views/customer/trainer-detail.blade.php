<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Detail | {{ $trainer->name }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- Fixed Sidebar --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name }}" />
            
            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
                <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
                
                <x-nav-item text="Trainer List" color="text-blue-600" src="user.svg" location="customer.trainers.index" style="bg-blue-50 border-r-4 border-blue-500" />
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>
            
            {{-- User Profile Section --}}
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
                        <p class="text-xs text-gray-500">Customer</p>
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

        {{-- Main Content Area --}}
        <div class="flex-1 ml-64">
            {{-- Scrollable Content --}}
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">
                {{-- Back Navigation --}}
                <div class="mb-6">
                    <a href="{{ route('customer.trainers.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Back to Trainers</span>
                    </a>
                </div>

                {{-- Trainer Profile Header --}}
                <div class="w-full bg-white rounded-xl shadow-lg border relative overflow-hidden mb-6">
                    
                    {{-- Banner --}}
                    <div class="relative overflow-hidden rounded-t-xl h-36" style="background: #dbeafe;">
                        <div class="absolute top-4 right-4">
                            <div class="flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-full px-3 py-1.5 text-black text-sm font-medium">
                                <i class="fas fa-dumbbell"></i>
                                <span>Personal Trainer</span>
                            </div>
                        </div>
                    </div>

                    {{-- Profile Card Content --}}
                    <div class="relative flex flex-col md:flex-row p-6 pt-0">
                        
                        {{-- Left Column (Profile Summary) --}}
                        <div class="w-full md:w-1/3 text-center md:text-left -mt-16 md:-mt-12 md:pr-6">
                            
                            <div class="flex flex-col items-center md:items-start space-y-4">
                                @if($trainer->image_url)
                                <img class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover" src="{{ $trainer->image_url }}" alt="{{ $trainer->name }}">
                                @else
                                <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-2xl"></i>
                                </div>
                                @endif
                                
                                <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $trainer->name }}</h1>
                                <p class="text-sm text-gray-500">Personal Trainer</p>
                                
                                {{-- Status Badge --}}
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-user-check mr-1.5"></i>
                                    Active Trainer
                                </span>
                            </div>
                        </div>
                        
                        {{-- Right Column (Trainer Cards) --}}
                        <div class="w-full md:w-2/3 md:pl-6 border-t md:border-t-0 md:border-l pt-8 md:pt-4 mt-6 md:mt-0">
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                {{-- Trainer Information Card --}}
                                <div class="bg-white rounded-lg border p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Trainer Information</h3>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-sm text-gray-600">Email</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $trainer->email }}</p>
                                        </div>
                                        @if($trainer->phone)
                                        <div>
                                            <p class="text-sm text-gray-600">Phone</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $trainer->phone }}</p>
                                        </div>
                                        @endif
                                        @if($trainer->address)
                                        <div>
                                            <p class="text-sm text-gray-600">Address</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $trainer->address }}</p>
                                        </div>
                                        @endif
                                        <div>
                                            <p class="text-sm text-gray-600">Trainer since</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $trainer->created_at->format('F Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Work Status Card --}}
                                <div class="bg-white rounded-lg border p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Work Status</h3>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-sm text-gray-600">Status</p>
                                            <p class="text-sm font-medium text-green-600">Active Trainer</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Specialization</p>
                                            <p class="text-sm font-medium text-gray-900">Personal Training</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Last updated</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $trainer->updated_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Classes and Activities --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Classes Handled --}}
                    <div class="bg-white rounded-xl border shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-chalkboard-teacher mr-2 text-sky-500"></i>
                                Classes Handled
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($classes->count() > 0)
                            <div class="space-y-4">
                                @foreach($classes->take(4) as $class)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-sky-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-dumbbell text-sky-600 text-sm"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $class->type }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($class->description ?? 'No description', 40) }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 font-medium">{{ $class->members->count() }}/{{ $class->capacity }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($class->schedule)->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if($classes->count() > 4)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">and {{ $classes->count() - 4 }} more classes...</p>
                            </div>
                            @endif
                            @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-chalkboard-teacher text-3xl mb-3"></i>
                                <p class="text-sm">No classes assigned yet</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Recent Bookings --}}
                    <div class="bg-white rounded-xl border shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-sky-500"></i>
                                Recent Bookings
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($recentActivities->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentActivities as $activity)
                                <div class="flex items-center justify-between p-3 bg-sky-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-sky-500 rounded-full mr-3"></div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-900">{{ $activity->membership->user->name ?? 'Client' }}</span>
                                            <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }} - {{ $activity->time }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-600 font-medium">{{ $activity->duration }}m</span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-alt text-3xl mb-3"></i>
                                <p class="text-sm">No recent bookings</p>
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
