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
                        <p class="text-sm font-bold text-gray-900">Profile Settings</p>
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

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-4">Aktivitas Saya</div>
                
                <a href="{{ route('trainer.bookings') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Booking Saya</span>
                </a>
            </nav>
            
            {{-- User Profile Section --}}
            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <a href="{{ route('trainer.profile.edit') }}" class="flex items-center flex-1 bg-blue-50 rounded-lg p-2 transition-colors">
                    @if(Auth::user()->profile_photo_path)
                        <img class="w-8 h-8 rounded-full object-cover" 
                             src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                             alt="{{ Auth::user()->name }}">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center">
                            <span class="text-white text-xs font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-600">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-blue-500">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                </a>
                
                <div>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                            class="flex items-center text-gray-500 hover:text-gray-700 transition-colors p-2 rounded-lg hover:bg-gray-50"
                            title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 ml-64 bg-gray-100">
            <main class="pt-8 pb-8 px-8 h-screen overflow-y-auto">
                {{-- Header Banner --}}
                <div style="background-color: #dbeafe;" class="rounded-lg p-8 mb-8 shadow-sm">
                    <div class="max-w-4xl">
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">Trainer Profile Settings</h1>
                        <p class="text-gray-600 text-lg leading-relaxed">
                            "Excellence is never an accident. It is always the result of high intention, sincere effort, and intelligent execution; 
                            it represents the wise choice of many alternatives." - Aristotle
                        </p>
                        <p class="text-gray-600 text-sm mt-4 opacity-75">
                            Manage your profile information and keep your details up to date for the best training experience.
                        </p>
                    </div>
                </div>

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">Success!</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Profile Form --}}
                <div class="bg-white rounded-lg shadow-lg">
                    {{-- Card Header --}}
                    <div class="px-8 py-6 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900">Profile Information</h2>
                        <p class="text-gray-600 mt-1">Update your personal details and profile photo</p>
                    </div>

                    {{-- Form Content --}}
                    <div class="px-8 py-6">
                        <form method="POST" action="{{ route('trainer.profile.update') }}" enctype="multipart/form-data" x-data="{
                            photoPreview: null,
                            updatePreview() {
                                const photo = $refs.photo.files[0];
                                if (!photo) return;
                                
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.photoPreview = e.target.result;
                                };
                                reader.readAsDataURL(photo);
                            }
                        }">
                            @csrf
                            @method('PUT')

                            {{-- Profile Photo Section --}}
                            <div class="mb-8">
                                <label class="block text-sm font-medium text-gray-700 mb-4">Profile Photo</label>
                                
                                <div class="flex items-center space-x-6">
                                    {{-- Current Photo --}}
                                    <div class="shrink-0">
                                        <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 border-4 border-white shadow-lg">
                                            @if(Auth::user()->profile_photo_path)
                                                <img x-show="!photoPreview" 
                                                     class="w-full h-full object-cover" 
                                                     src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                                     alt="{{ Auth::user()->name }}">
                                            @else
                                                <div x-show="!photoPreview" class="w-full h-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center">
                                                    <span class="text-white text-2xl font-semibold">
                                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                            
                                            {{-- Preview New Photo --}}
                                            <img x-show="photoPreview" 
                                                 x-bind:src="photoPreview" 
                                                 class="w-full h-full object-cover"
                                                 style="display: none;">
                                        </div>
                                    </div>

                                    {{-- Upload Controls --}}
                                    <div class="flex-1">
                                        <input type="file" 
                                               x-ref="photo"
                                               name="photo" 
                                               id="photo" 
                                               accept="image/*"
                                               @change="updatePreview()"
                                               class="hidden">
                                        
                                        <label for="photo" 
                                               class="cursor-pointer inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-camera mr-2"></i>
                                            Change Photo
                                        </label>
                                        
                                        <p class="text-sm text-gray-500 mt-2">
                                            JPG, PNG or GIF (max. 2MB)
                                        </p>
                                        
                                        @error('photo')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Form Fields --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Name --}}
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name', Auth::user()->name) }}" 
                                           required
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           value="{{ old('email', Auth::user()->email) }}" 
                                           required
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" 
                                           name="phone" 
                                           id="phone" 
                                           value="{{ old('phone', Auth::user()->phone) }}" 
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                           placeholder="Enter your phone number">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Role (Read-only) --}}
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                    <input type="text" 
                                           value="{{ ucfirst(Auth::user()->role) }}" 
                                           disabled
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 text-gray-500 cursor-not-allowed">
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <div class="flex justify-end space-x-4">
                                    <a href="{{ route('trainer.dashboard') }}" 
                                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                        Cancel
                                    </a>
                                    <button type="submit" 
                                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                        <i class="fas fa-save mr-2"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Additional Information Card --}}
                <div class="mt-8 bg-white rounded-lg shadow-lg">
                    <div class="px-8 py-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-info-circle text-blue-600"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Account Information</h3>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Member since:</span> 
                                        {{ Auth::user()->created_at->format('F d, Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Last updated:</span> 
                                        {{ Auth::user()->updated_at->format('F d, Y \a\t g:i A') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Account status:</span> 
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
