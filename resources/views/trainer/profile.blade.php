<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile Settings | {{ Auth::user()->name }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- FIXED SIDEBAR --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            {{-- Header Sidebar --}}
            <div class="h-16 flex items-center px-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-lg flex items-center justify-center bg-indigo-600">
    <img src="{{ asset('icons/barbell.svg') }}" 
         alt="Logo"
         class="w-6 h-6 invert brightness-0">
</div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Dashboard</p>
                        <p class="text-xs text-gray-500">Welcome, {{ explode(' ', Auth::user()->name)[0] }}!</p>
                    </div>
                </div>
            </div>

            {{-- Navigation Menu --}}
            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                
                <a href="{{ route('trainer.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </nav>
            
            {{-- User Profile Section --}}
            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <a href="{{ route('trainer.profile.edit') }}" class="flex items-center flex-1">
                    @if(Auth::user()->profile_photo_path)
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-gray-400 text-sm"></i>
                        </div>
                    @endif
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                </a>
                <div>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                            class="flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 ml-64">
            {{-- Scrollable Content --}}
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto">
                {{-- Back Navigation --}}
                <div class="mb-6">
                    <a href="{{ route('trainer.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Back to Dashboard</span>
                    </a>
                </div>

                {{-- Profile Header --}}
                <div class="w-full bg-white rounded-xl shadow-lg border relative overflow-hidden mb-6">
                    
                    {{-- Banner --}}
                    <div class="relative overflow-hidden rounded-t-xl h-36" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    </div>

                    {{-- Profile Card Content --}}
                    <div class="relative flex flex-col md:flex-row p-6 pt-0">
                        
                        {{-- Left Column (Profile Summary) --}}
                        <div class="w-full md:w-1/3 text-center md:text-left -mt-16 md:-mt-12 md:pr-6">
                            
                            <div class="flex flex-col items-center md:items-start space-y-4">
                                @if(Auth::user()->profile_photo_path)
                                    <img class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                
                                <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ Auth::user()->name }}</h1>
                                <p class="text-sm text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                                
                                {{-- Status Badge --}}
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1.5"></i>
                                    Active
                                </span>

                                {{-- Quote --}}
                                <div class="bg-gray-50 rounded-lg p-4 mt-4 w-full">
                                    <p class="text-xs text-gray-500 italic text-center md:text-left">
                                        "Leading with vision, managing with precision, empowering growth."
                                    </p>
                                </div>

                                {{-- Additional Info --}}
                                <div class="w-full space-y-3 pt-4 border-t">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Role</span>
                                        <span class="text-sm font-medium text-blue-600">{{ ucfirst(Auth::user()->role) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Access Level</span>
                                        <span class="text-sm font-medium text-green-600">Full Control</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Status</span>
                                        <span class="text-sm font-medium text-blue-600">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Right Column (Profile Settings) --}}
                        <div class="w-full md:w-2/3 md:pl-6 border-t md:border-t-0 md:border-l pt-8 md:pt-4 mt-6 md:mt-0" x-data="{ activeTab: 'account' }">
                            
                            {{-- Tabs --}}
                            <div class="border-b border-gray-200 mb-6">
                                <nav class="flex space-x-8">
                                    <button @click="activeTab = 'account'" 
                                            :class="activeTab === 'account' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                        Account Settings
                                    </button>
                                    <button @click="activeTab = 'security'" 
                                            :class="activeTab === 'security' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                        Password & Security
                                    </button>
                                </nav>
                            </div>

                            {{-- Account Settings Tab --}}
                            <div x-show="activeTab === 'account'" x-transition>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Profile Information</h3>
                                <p class="text-sm text-gray-500 mb-6">Update your account's profile information and email address.</p>

                                @if(session('success'))
                                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <form action="{{ route('trainer.profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    {{-- Photo Profile --}}
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Photo Profile</label>
                                        <div class="flex flex-col items-start space-y-3">
                                            @if(Auth::user()->profile_photo_path)
                                                <img id="preview-photo" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                                     alt="{{ Auth::user()->name }}" 
                                                     class="h-20 w-20 rounded-full object-cover border-2 border-gray-200">
                                            @else
                                                <div id="preview-photo" class="h-20 w-20 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl border-2 border-gray-200">
                                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="w-full">
                                                <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/gif" onchange="previewImage(event)"
                                                       class="block w-full text-sm text-gray-500 border-0 cursor-pointer bg-transparent focus:outline-none file:bg-blue-50 file:border-0 file:mr-4 file:py-2 file:px-5 file:rounded-full file:text-sm file:font-medium file:text-blue-600 hover:file:bg-blue-100">
                                                <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (Max. 2MB)</p>
                                            </div>
                                        </div>
                                        @error('photo')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Form Grid --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                            <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                                            @error('name')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                            <input type="text" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                            <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                                            @error('email')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">User Role</label>
                                            <input type="text" id="role" name="role" value="{{ ucfirst(Auth::user()->role) }}" disabled
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                                            <p class="text-xs text-gray-500 mt-1">Role cannot be changed manually.</p>
                                        </div>
                                    </div>

                                    {{-- Save Button --}}
                                    <div class="flex justify-start">
                                        <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-md transition-colors uppercase">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- Password & Security Tab --}}
                            <div x-show="activeTab === 'security'" x-transition>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Update Password</h3>
                                <p class="text-sm text-gray-600 mb-6">Ensure your account is using a long, random password to stay secure.</p>

                                <form action="#" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="space-y-5">
                                        <div>
                                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                            <input type="password" id="current_password" name="current_password"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                            <input type="password" id="new_password" name="new_password"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                            <input type="password" id="confirm_password" name="confirm_password"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div class="flex justify-end pt-2">
                                            <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-md transition-colors uppercase">
                                                Update Password
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                {{-- Delete Account Section --}}
                                <div class="mt-10 border-t pt-8">
                                    <h3 class="text-xl font-bold text-red-600 mb-2">Delete Account</h3>
                                    <p class="text-sm text-gray-600 mb-6">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

                                    <button type="button" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-md transition-colors uppercase inline-flex items-center">
                                        <i class="fas fa-trash mr-2"></i>
                                        Delete Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview-photo');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.id = 'preview-photo';
                        img.className = 'h-20 w-20 rounded-full object-cover border-2 border-gray-200';
                        img.src = e.target.result;
                        preview.parentNode.replaceChild(img, preview);
                    }
                }
                
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
