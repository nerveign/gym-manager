<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking | Customer</title>
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
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
                {{-- Menu Active --}}
                <x-nav-item text="My Bookings" color="text-zinc-700" src="calendar.svg" location="customer.bookings.index" style="bg-blue-50 border-r-4 border-blue-500" />

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
            
            {{-- 1. HEADER BAR (Sesuai Referensi) --}}
            <div class="bg-white border-b px-8 py-4 flex justify-between items-center shadow-sm shrink-0 z-20">
                <div class="flex items-center gap-4">
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('customer.bookings.index') }}" 
                       class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 hover:text-blue-600 transition shadow-sm">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </a>
                    <h1 class="text-xl font-bold text-gray-900">Buat Booking Baru</h1>
                </div>
            </div>

            {{-- 2. FORM AREA --}}
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-8">

                    <form action="{{ route('customer.bookings.store') }}" method="POST">
                        @csrf

                        {{-- INFO MEMBERSHIP (Ditampilkan dalam box biru muda agar rapi) --}}
                        <div class="mb-8 p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-center gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-blue-600 uppercase tracking-wide">Active Membership</label>
                                <div class="flex items-center gap-2 text-gray-900 font-medium">
                                    <span>{{ auth()->user()->name }}</span>
                                    <span class="text-gray-400">•</span>
                                    <span class="text-sm text-gray-600">Expires: {{ \Carbon\Carbon::parse($activeMembership->end_time)->format('d M Y') }}</span>
                                </div>
                            </div>
                            {{-- Input Hidden Membership ID --}}
                            <input type="hidden" name="membership_id" value="{{ $activeMembership->id }}">
                        </div>

                        {{-- INPUT 1: TRAINER (Full Width) --}}
                        <div class="mb-6">
                            <label for="trainer_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Trainer</label>
                            <select id="trainer_id" name="trainer_id" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                                <option value="" disabled selected>-- Pilih Trainer Profesional --</option>
                                @foreach($trainers as $trainer)
                                    <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                        {{ $trainer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('trainer_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- GRID 2 KOLOM: DATE & TIME --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            
                            {{-- Input Date --}}
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                                <input type="date" id="date" name="date"
                                       value="{{ old('date') }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                       required>
                                @error('date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Input Time --}}
                            <div>
                                <label for="time" class="block text-sm font-medium text-gray-700 mb-2">Pilih Jam</label>
                                <input type="time" id="time" name="time"
                                       value="{{ old('time') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                       required>
                                @error('time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- INPUT DURASI (Disabled / Read Only) --}}
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Sesi</label>
                            <div class="relative">
                                <input type="text" value="60 Menit" disabled 
                                       class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-500 cursor-not-allowed shadow-sm pl-10">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="far fa-clock text-gray-400"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">*Durasi standar sesi latihan personal adalah 60 menit.</p>
                            
                            {{-- Hidden input durasi (wajib ada agar data terkirim) --}}
                            <input type="hidden" name="duration" value="60">
                        </div>

                        {{-- ACTION BUTTONS (Sesuai Referensi) --}}
                        <div class="flex items-center justify-end gap-4 border-t pt-6">
                            <a href="{{ route('customer.bookings.index') }}" 
                               class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                                <i class="fas fa-check"></i>
                                Booking Sekarang
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</body>
</html>