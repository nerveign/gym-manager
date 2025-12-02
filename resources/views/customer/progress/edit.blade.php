<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Progress | Customer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- ================= SIDEBAR (Sama Persis dengan Halaman Lain) ================= --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ auth()->user()->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                {{-- Menu Progress Tracking kita buat ACTIVE --}}
                <x-nav-item text="Progress Tracking" color="text-zinc-700" src="barbell.svg" location="customer.progress.index" style="bg-blue-50 border-r-4 border-blue-500" />
                <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" /> 

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
                <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ auth()->user()->image_url ?? asset('images/default-user.png') }}" alt="{{ auth()->user()->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
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

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="flex-1">
            {{-- Padding kiri (ml-64) agar konten tidak tertutup sidebar --}}
            <main class="ml-64 min-h-screen bg-gray-100 p-6">
                
                {{-- Header Halaman --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Edit Progress Record
                    </h2>
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('customer.progress.index') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to List
                    </a>
                </div>

                {{-- Form Edit Container --}}
                <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    
                    {{-- Pastikan variabel $progress dikirim dari controller (biasanya model binding) --}}
                    <form action="{{ route('customer.progress.update', $progress->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Input: Exercise Name --}}
                        <div class="mb-6">
                            <label for="exercise" class="block text-sm font-medium text-gray-700 mb-2">Exercise / Activity Name</label>
                            <input type="text" 
                                   name="exercise" 
                                   id="exercise" 
                                   value="{{ old('exercise', $progress->exercise) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm"
                                   placeholder="e.g. Morning Cardio, Bench Press" 
                                   required>
                            @error('exercise')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Input: Duration --}}
                        <div class="mb-6">
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                            <input type="number" 
                                   name="duration" 
                                   id="duration" 
                                   value="{{ old('duration', $progress->duration) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm"
                                   placeholder="e.g. 45" 
                                   required>
                            @error('duration')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Input: Description --}}
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Notes / Description (Optional)</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4" 
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm"
                                      placeholder="How did it feel? Any personal records?">{{ old('description', $progress->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('customer.progress.index') }}" 
                               class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Progress
                            </button>
                        </div>
                    </form>
                </div>

            </main>
        </div>
    </div>
</body>
</html>