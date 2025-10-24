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
            /* Placeholder: Pastikan Anda memiliki file ini di public/images/ */
            background-image: url('{{ asset('images/gym-hero.jpeg') }}'); 
            background-size: cover;
            background-position: center 30%;
        }
    </style>
    <script>
        // Alpine.js helper untuk menangani fokus pada modal saat ada error validasi
        document.addEventListener('alpine:init', () => {
            Alpine.data('profileTabs', () => ({
                activeTab: 'account',
                
                // Pindah ke tab security jika ada error di formulir password atau delete user
                init() {
                    @if($errors->updatePassword->isNotEmpty() || $errors->userDeletion->isNotEmpty())
                        this.activeTab = 'security';
                    @endif
                },
                
                // Fungsi untuk menangani navigasi tab
                setActive(tab) {
                    this.activeTab = tab;
                }
            }));
        });
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            
            <x-dashboard-header name="{{ auth()->user()->name }}" />
            
            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                
                {{-- Navigasi Sesuai Role --}}
                @php $role = auth()->user()->role; @endphp
                @if($role === 'customer')
                    <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />
                    <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                    <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
                @elseif($role === 'admin')
                    <x-nav-item text="Dashboard" color="text-gray-600" src="home.svg" location="admin.dashboard" />
                    <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Manajemen Data</div>
                    <x-nav-item text="Users" color="text-gray-600" src="users.svg" location="admin.users_management" />
                @else
                    <x-nav-item text="Dashboard" color="text-gray-600" src="home.svg" location="trainer.dashboard" />
                @endif
                
            </nav>
            
            <div class="absolute bottom-0 w-64 p-4 flex justify-between items-center bg-blue-50 border-r-4 border-blue-500 transition-colors duration-200">
                <div class="flex items-center">
                    <img class="w-8 h-8 rounded-full" src="{{ auth()->user()->image_url ?? asset('images/default-user.png') }}" alt="{{ auth()->user()->name }}">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-700">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-500">{{ ucfirst($role) }}</p>
                    </div>
                </div>
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center p-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                                <img src="{{ asset('icons/logout.svg') }}" alt="logout">
                            </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex-1 ml-64 p-4 h-screen overflow-y-auto scroll-container" x-data="profileTabs()">
            
            <h2 class="text-3xl font-bold text-gray-900 mb-6">User Profile Settings</h2>

            <div class="w-full bg-white rounded-xl shadow-lg border relative overflow-hidden mb-6">
                
                {{-- Banner --}}
                <div class="profile-banner"></div>

                {{-- Profile Card Content --}}
                <div class="relative flex flex-col md:flex-row p-6 pt-0">
                    
                    {{-- Left Column (Profile Summary) --}}
                    <div class="w-full md:w-1/3 text-center md:text-left -mt-16 md:-mt-12 md:pr-6">
                        
                        <div class="flex flex-col items-center md:items-start space-y-4">
                            <img class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-gray-200 object-cover" 
                                 src="{{ auth()->user()->image_url ?? asset('images/default-user.png') }}" 
                                 alt="{{ auth()->user()->name }}">
                            
                            <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ auth()->user()->name }}</h1>
                            <p class="text-sm text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                            
                            {{-- Quick Stats Area --}}
                            <div class="w-full border-t pt-4 space-y-3 mt-4">
                                @if(auth()->user()->role === 'customer')
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Email</span>
                                        <span class="font-semibold text-gray-600 truncate">{{ $user->email }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Phone</span>
                                        <span class="font-semibold text-gray-600">{{ $user->phone ?? 'N/A' }}</span>
                                    </div>
                                @endif
                                {{-- Link View Public Profile DIHAPUS sesuai permintaan --}}
                            </div>
                        </div>
                    </div>
                    
                    {{-- Right Column (Tabs & Forms) --}}
                    <div class="w-full md:w-2/3 md:pl-6 border-t md:border-t-0 md:border-l pt-6 md:pt-0 mt-6 md:mt-0">
                        
                        {{-- Tabs Navigation --}}
                        <div class="flex space-x-6 border-b pb-3 mb-6">
                            <button 
                                @click="setActive('account')"
                                :class="{
                                    'text-blue-600 border-blue-600': activeTab === 'account',
                                    'text-gray-500 hover:text-gray-800 border-transparent': activeTab !== 'account'
                                }"
                                class="text-lg font-semibold border-b-2 pb-2 transition-colors duration-200 focus:outline-none"
                            >
                                Account Settings
                            </button>
                            <button 
                                @click="setActive('security')"
                                :class="{
                                    'text-blue-600 border-blue-600': activeTab === 'security',
                                    'text-gray-500 hover:text-gray-800 border-transparent': activeTab !== 'security'
                                }"
                                class="text-lg font-semibold border-b-2 pb-2 transition-colors duration-200 focus:outline-none"
                            >
                                Password & Security
                            </button>
                        </div>

                        {{-- Tab 1: Account Settings Content --}}
                        <div x-show="activeTab === 'account'" x-transition:enter.duration.500ms x-transition:leave.duration.300ms>
                            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-6">
                                {{-- Update Profile Information Form --}}
                                {{-- Konten Form dipindahkan ke partials dan disesuaikan untuk layout ini --}}
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>

                        {{-- Tab 2: Password & Security Content --}}
                        <div x-show="activeTab === 'security'" x-transition:enter.duration.500ms x-transition:leave.duration.300ms>
                            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-6">
                                {{-- Update Password Form --}}
                                @include('profile.partials.update-password-form')
                            </div>

                            {{-- Delete Account Form (Warna Merah untuk Danger Zone) --}}
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