<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | FitAja</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .profile-banner {
            height: 150px;
            background: #dbeafe; /* bg-blue-100 */
            background-size: cover;
            background-position: center;
        }
    </style>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('profileTabs', () => ({
                activeTab: 'account',
                init() {
                    @if($errors->updatePassword->isNotEmpty() || $errors->userDeletion->isNotEmpty())
                        this.activeTab = 'security';
                    @endif
                },
                setActive(tab) {
                    this.activeTab = tab;
                }
            }));
        });
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- SIDEBAR --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            
            <x-dashboard-header name="{{ auth()->user()->name }}" />
            
            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                
                @php $role = auth()->user()->role; @endphp
                
                @if($role === 'trainer')
                    <x-nav-item text="Dashboard" color="text-gray-600" src="home.svg" location="trainer.dashboard" />
                    <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">My Activities</div>
                    <x-nav-item text="Booking Saya" color="text-gray-600" src="calendar.svg" location="trainer.bookings" />
                @else
                    <x-nav-item text="Dashboard" color="text-gray-600" src="home.svg" location="dashboard" />
                @endif
            </nav>
            
            {{-- User Profile Section (Sidebar Bottom) --}}
            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <div class="flex items-center">
                    @if(auth()->user()->image_url)
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ auth()->user()->image_url }}" alt="{{ auth()->user()->name }}">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-gray-400 text-sm"></i>
                        </div>
                    @endif
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst($role) }}</p>
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

        {{-- MAIN CONTENT --}}
        <div class="flex-1 ml-64 p-4 h-screen overflow-y-auto scroll-container" x-data="profileTabs()">
            
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Trainer Profile Settings</h2>

            <div class="w-full bg-white rounded-xl shadow-lg border relative overflow-hidden mb-6">
                
                {{-- Banner --}}
                <div class="profile-banner"></div>

                {{-- Profile Card Content --}}
                <div class="relative flex flex-col md:flex-row p-6 pt-0">
                    
                    {{-- Left Column (Profile Summary) --}}
                    <div class="w-full md:w-1/3 text-center md:text-left -mt-16 md:-mt-12 md:pr-6">
                        
                        <div class="flex flex-col items-center md:items-start space-y-4">
                            {{-- Profile Image --}}
                            @if(auth()->user()->image_url)
                                <img class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover" 
                                     src="{{ auth()->user()->image_url }}" 
                                     alt="{{ auth()->user()->name }}">
                            @else
                                <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            
                            {{-- Name & Detail Section --}}
                            <div class="text-center md:text-left w-full">
                                <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ auth()->user()->name }}</h1>
                                
                                {{-- Motto --}}
                                <p class="text-gray-500 text-sm mt-1 italic">
                                    "{{ auth()->user()->motto ?? 'Keep pushing your limits!' }}"
                                </p>

                                {{-- Badges Section (Aligned Left on Desktop) --}}
                                <div class="mt-4 flex flex-col items-center md:items-start gap-2">
                                    
                                    {{-- Row 1: Role --}}
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-briefcase w-5 text-center mr-2 text-gray-400"></i>
                                        <span class="font-medium">Role:</span>
                                        <span class="ml-2 px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                            {{ ucfirst(auth()->user()->role) }}
                                        </span>
                                    </div>

                                    {{-- Row 3: Status --}}
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-toggle-on w-5 text-center mr-2 text-gray-400"></i>
                                        <span class="font-medium">Status:</span>
                                        <span class="ml-2 px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            Active
                                        </span>
                                    </div>

                                </div>
                            </div>
                            
                            {{-- Quick Stats Area --}}
                            <div class="w-full border-t pt-4 space-y-3 mt-4">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Email</span>
                                    <span class="font-semibold text-gray-600 truncate max-w-[150px]" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Phone</span>
                                    <span class="font-semibold text-gray-600">{{ auth()->user()->phone ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Joined</span>
                                    <span class="font-semibold text-gray-600">{{ auth()->user()->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Right Column (Tabs & Forms) --}}
                    <div class="w-full md:w-2/3 md:pl-6 border-t md:border-t-0 md:border-l pt-8 md:pt-4 mt-6 md:mt-0">
                        
                        <div class="flex space-x-6 border-b pb-3 mb-6 mt-4">
                            <button 
                                @click="setActive('account')"
                                :class="{ 'text-blue-600 border-blue-600': activeTab === 'account', 'text-gray-500 hover:text-gray-800 border-transparent': activeTab !== 'account' }"
                                class="text-lg font-semibold border-b-2 pb-2 transition-colors duration-200 focus:outline-none"
                            >
                                Account Settings
                            </button>
                            <button 
                                @click="setActive('security')"
                                :class="{ 'text-blue-600 border-blue-600': activeTab === 'security', 'text-gray-500 hover:text-gray-800 border-transparent': activeTab !== 'security' }"
                                class="text-lg font-semibold border-b-2 pb-2 transition-colors duration-200 focus:outline-none"
                            >
                                Password & Security
                            </button>
                        </div>

                        <div x-show="activeTab === 'account'" x-transition:enter.duration.500ms x-transition:leave.duration.300ms>
                            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-6">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>

                        <div x-show="activeTab === 'security'" x-transition:enter.duration.500ms x-transition:leave.duration.300ms>
                            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-6">
                                @include('profile.partials.update-password-form')
                            </div>
                            <div class="bg-white p-6 rounded-xl border border-red-200 shadow-sm">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>