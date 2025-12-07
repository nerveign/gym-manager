<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Progress | Customer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 overflow-hidden">
    <div class="flex h-screen">
        {{-- ================= SIDEBAR ================= --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ auth()->user()->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                <x-nav-item text="Progress Tracking" color="text-zinc-700" src="barbell.svg" location="customer.progress.index" style="bg-blue-50 border-r-4 border-blue-500" />
                <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />
                <x-nav-item text="My Classes" color="text-gray-600" src="class.svg" location="customer.my-classes" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
                <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        <img class="w-8 h-8 rounded-full object-cover"
                            src="{{ auth()->user()->image_url ?? asset('images/default-user.png') }}"
                            alt="{{ auth()->user()->name }}">
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
        <div class="flex-1 ml-64 h-screen flex flex-col">

            {{-- 1. HEADER BAR (Sesuai Style Baru) --}}
            <div class="bg-white border-b px-8 py-4 flex justify-between items-center shadow-sm shrink-0 z-20">
                <div class="flex items-center gap-4">
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('customer.progress.index') }}"
                        class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 hover:text-blue-600 transition shadow-sm">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </a>
                    <h1 class="text-xl font-bold text-gray-900">Edit Progress Record</h1>
                </div>
            </div>

            {{-- 2. FORM AREA --}}
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-8">

                    {{-- Form Update --}}
                    <form action="{{ route('customer.progress.update', $progress->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Wajib untuk update --}}

                        {{-- GRID 2 KOLOM --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                            {{-- Input 1: Exercise Name --}}
                            <div>
                                <label for="exercise" class="block text-sm font-medium text-gray-700 mb-2">Nama Latihan / Aktivitas</label>
                                <input type="text"
                                    name="exercise"
                                    id="exercise"
                                    value="{{ old('exercise', $progress->exercise) }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                    required>
                                @error('exercise')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Input 2: Duration --}}
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Durasi (Menit)</label>
                                <input type="number"
                                    name="duration"
                                    id="duration"
                                    value="{{ old('duration', $progress->duration) }}"
                                    min="1"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                    required>
                                @error('duration')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Full Width: Description --}}
                        <div class="mb-8">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Catatan / Deskripsi Lengkap</label>
                            <textarea name="description"
                                id="description"
                                rows="5"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('description', $progress->description) }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-4 border-t pt-6">
                            <a href="{{ route('customer.progress.index') }}"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</body>

</html>