<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking | Customer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- Fixed Sidebar (Sidebar) --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

                {{-- Nav-Item untuk Home (INACTIVE) --}}
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                {{-- Nav-Item untuk Progress Tracking (INACTIVE) --}}
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />

                {{-- Nav-Item untuk My Bookings (ACTIVE) --}}
                <x-nav-item text="My Bookings" color="text-zinc-700" src="calendar.svg" location="customer.bookings.index" style="bg-blue-50 border-r-4 border-blue-500" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>

                {{-- Nav-Item Trainer List (INACTIVE) --}}
                <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />

                {{-- Nav-Item Equipment (INACTIVE) --}}
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            {{-- User Profile Section --}}
            <div class="absolute bottom-0 w-64 p-4  flex justify-between bg-white border-t">
                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ $user->image_url ?? asset('images/default-user.png') }}" alt="{{ $user->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Customer</p>
                    </div>
                </div>
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center p-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 text-gray-500">
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Area Konten Utama (Main Content) --}}
        <div class="flex-1 ml-64">
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">

                {{-- Header Halaman --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Create New Booking</h2>
                    <a href="{{ route('customer.bookings.index') }}"
                       class="text-gray-600 hover:text-gray-900 font-medium flex items-center space-x-2">
                        <i class="fas fa-arrow-left w-4 h-4"></i>
                        <span>Back to My Bookings</span>
                    </a>
                </div>

                {{-- Form Booking --}}
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <form action="{{ route('customer.bookings.store') }}" method="POST">
                            @csrf
                            <div class="p-6">
                                <div class="space-y-6">

                                    {{-- Info Membership (hanya info) --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Active Membership</label>
                                        <div class="p-3 bg-gray-50 rounded-lg border">
                                            {{-- Perbaiki typo </sppan> menjadi </span> --}}
                                            <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-600">Expires on: {{ \Carbon\Carbon::parse($activeMembership->end_time)->format('d M Y') }}</p>
                                        </div>
                                        {{-- Hidden input untuk membership_id --}}
                                        <input type="hidden" name="membership_id" value="{{ $activeMembership->id }}">
                                    </div>

                                    <!-- Trainer Select Field -->
                                    <div>
                                        <label for="trainer_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Select Trainer *
                                        </label>
                                        <select id="trainer_id" name="trainer_id"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                                required>
                                            <option value="" disabled selected>Choose a trainer</option>
                                            @foreach($trainers as $trainer)
                                                <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                                    {{ $trainer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('trainer_id')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Date Field -->
                                        <div>
                                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                                Select Date *
                                            </label>
                                            <input type="date" id="date" name="date"
                                                   value="{{ old('date') }}"
                                                   min="{{ now()->format('Y-m-d') }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                                   required>
                                            @error('date')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Time Field -->
                                        <div>
                                            <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                                                Select Time *
                                            </label>
                                            <input type="time" id="time" name="time"
                                                   value="{{ old('time') }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                                   required>
                                            @error('time')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Duration Field (Disabled) -->
                                    <div>
                                        <label for="duration_display" class="block text-sm font-medium text-gray-700 mb-2">
                                            Duration (minutes)
                                        </label>
                                        {{-- Tambahkan atribut disabled dan ubah id --}}
                                        <input type="number" id="duration_display"
                                               value="60" {{-- Default 60 menit --}}
                                               disabled {{-- Nonaktifkan input --}}
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"> {{-- Styling disabled --}}

                                        {{-- Tambahkan input hidden untuk mengirim nilai 60 --}}
                                        <input type="hidden" name="duration" value="60">

                                        {{-- Error 'duration' tidak relevan lagi jika disabled, tapi bisa dibiarkan --}}
                                        @error('duration')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="bg-gray-50 px-6 py-4 border-t rounded-b-xl flex items-center justify-end space-x-4">
                                <a href="{{ route('customer.bookings.index') }}"
                                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                                    <i class="fas fa-check w-4 h-4"></i>
                                    <span>Book Now</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>