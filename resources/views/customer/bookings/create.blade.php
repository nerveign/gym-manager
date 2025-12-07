<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                                {{-- Availability status --}}
                                <div id="availability-status" class="mt-2 text-sm" style="display: none;"></div>
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

    {{-- Popup Modal for Booking Conflict --}}
    <div id="booking-conflict-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Jadwal Tidak Tersedia
                            </h3>
                            <div class="mt-2">
                                <p id="conflict-message" class="text-sm text-gray-500">
                                    Trainer sudah dibooking pada tanggal dan jam tersebut. Silakan pilih waktu lain.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeConflictModal()" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Pilih Waktu Lain
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let availabilityCheckTimeout;

        // Function to check availability
        function checkAvailability() {
            const trainerId = document.getElementById('trainer_id').value;
            const date = document.getElementById('date').value;
            const time = document.getElementById('time').value;
            const statusDiv = document.getElementById('availability-status');

            if (!trainerId || !date || !time) {
                statusDiv.style.display = 'none';
                return;
            }

            // Clear previous timeout
            if (availabilityCheckTimeout) {
                clearTimeout(availabilityCheckTimeout);
            }

            // Show loading
            statusDiv.style.display = 'block';
            statusDiv.className = 'mt-2 text-sm text-gray-500';
            statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengecek ketersediaan...';

            // Delay the check to avoid too many requests
            availabilityCheckTimeout = setTimeout(() => {
                fetch('{{ route("customer.bookings.check-availability") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            trainer_id: trainerId,
                            date: date,
                            time: time
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.available) {
                            statusDiv.className = 'mt-2 text-sm text-green-600';
                            statusDiv.innerHTML = '<i class="fas fa-check-circle mr-2"></i>' + data.message;
                        } else {
                            statusDiv.className = 'mt-2 text-sm text-red-600';
                            statusDiv.innerHTML = '<i class="fas fa-times-circle mr-2"></i>' + data.message;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        statusDiv.className = 'mt-2 text-sm text-gray-500';
                        statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Gagal mengecek ketersediaan';
                    });
            }, 500); // Wait 500ms after user stops typing/selecting
        }

        // Function to show conflict modal
        function showConflictModal(message) {
            document.getElementById('conflict-message').textContent = message;
            document.getElementById('booking-conflict-modal').classList.remove('hidden');
        }

        // Function to close conflict modal
        function closeConflictModal() {
            document.getElementById('booking-conflict-modal').classList.add('hidden');
        }

        // Add event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const trainerSelect = document.getElementById('trainer_id');
            const dateInput = document.getElementById('date');
            const timeInput = document.getElementById('time');
            const form = document.querySelector('form');

            // Check availability when inputs change
            trainerSelect.addEventListener('change', checkAvailability);
            dateInput.addEventListener('change', checkAvailability);
            timeInput.addEventListener('change', checkAvailability);

            // Prevent form submission if slot is not available
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const trainerId = trainerSelect.value;
                const date = dateInput.value;
                const time = timeInput.value;

                if (!trainerId || !date || !time) {
                    this.submit();
                    return;
                }

                // Double-check availability before submission
                fetch('{{ route("customer.bookings.check-availability") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            trainer_id: trainerId,
                            date: date,
                            time: time
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.available) {
                            // Slot is available, submit the form
                            form.submit();
                        } else {
                            // Slot is not available, show popup
                            showConflictModal(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // If there's an error checking, allow form submission (fallback to server validation)
                        form.submit();
                    });
            });
        });
    </script>
</body>

</html>