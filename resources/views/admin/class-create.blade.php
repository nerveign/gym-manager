<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'FitAja') }} - Tambah Kelas</title>

  {{-- Fonts & Styles --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">

    {{-- ================= SIDEBAR ADMIN ================= --}}
    <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">

      <x-dashboard-header name="{{ auth()->user()->name }}" />

      <nav class="mt-6">
        <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

        <x-nav-item text="Dashboard" color="text-zinc-700" src="home.svg" location="admin.dashboard" />

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Manajemen Data</div>

        <x-nav-item text="Users" color="text-zinc-700" src="users.svg" location="admin.users_management" />
        <x-nav-item text="Trainers" color="text-zinc-700" src="user.svg" location="admin.trainers_management" />

        {{-- MENU AKTIF --}}
        <x-nav-item text="Classes" color="text-zinc-700" src="class.svg" location="admin.classes_management" style="bg-blue-50 border-r-4 border-blue-500" />

        <x-nav-item text="Bookings" color="text-zinc-700" src="calendar.svg" location="admin.bookings_management" />
        <x-nav-item text="Equipment" color="text-zinc-700" src="equipment.svg" location="admin.equipments_management" />
        <x-nav-item text="Transactions" color="text-zinc-700" src="dollar-sign.svg" location="admin.transactions_management" />
      </nav>

      {{-- User Profile Section --}}
      <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
        <div class="flex items-center">
          <a href="{{ route('profile.edit') }}">
            @if(auth()->user()->image_url)
            <img class="w-8 h-8 rounded-full object-cover border border-gray-200"
              src="{{ auth()->user()->image_url }}"
              alt="{{ auth()->user()->name }}">
            @else
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center border border-gray-300 text-gray-400">
              <i class="fas fa-user text-xs"></i>
            </div>
            @endif
          </a>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">Admin</p>
          </div>
        </div>
        <div>
          <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
            @csrf
          </form>
          <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
            <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 mr-1">
          </button>
        </div>
      </div>
    </div>

    {{-- ================= MAIN CONTENT AREA ================= --}}
    <div class="flex-1 ml-64 bg-gray-100 h-screen overflow-hidden">
      <main class="pt-8 pb-8 px-8 h-full overflow-y-auto scroll-container">

        {{-- Header & Breadcrumb --}}
        <div class="max-w-3xl mx-auto mb-6">

          <h1 class="text-3xl font-bold text-gray-900 mt-1">Tambah Kelas Baru</h1>
          <p class="text-gray-500 mt-1 text-sm">Isi formulir di bawah untuk menambahkan jadwal kelas baru.</p>
        </div>

        {{-- Form Container --}}
        <div class="max-w-3xl mx-auto">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-8 border border-gray-100">

            <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-6">
              @csrf

              {{-- Nama Kelas --}}
              <div>
                <x-input-label for="type" :value="__('Nama Kelas / Tipe')" />
                <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" :value="old('type')" required autofocus placeholder="Contoh: Yoga Morning, HIIT Cardio" />
                <x-input-error :messages="$errors->get('type')" class="mt-2" />
              </div>

              {{-- Trainer --}}
              <div>
                <label for="trainer_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Trainer</label>
                <select id="trainer_id" name="trainer_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                  <option value="">-- Pilih Trainer --</option>
                  @foreach($trainers as $trainer)
                  <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                    {{ $trainer->name }} ({{ $trainer->specialization ?? 'General' }})
                  </option>
                  @endforeach
                </select>
                <x-input-error :messages="$errors->get('trainer_id')" class="mt-2" />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Jadwal (DateTime) --}}
                <div>
                  <x-input-label for="schedule" :value="__('Jadwal Kelas')" />
                  <x-text-input id="schedule" class="block mt-1 w-full" type="datetime-local" name="schedule" :value="old('schedule')" required />
                  <x-input-error :messages="$errors->get('schedule')" class="mt-2" />
                </div>

                {{-- Kapasitas --}}
                <div>
                  <x-input-label for="capacity" :value="__('Kapasitas Maksimal')" />
                  <x-text-input id="capacity" class="block mt-1 w-full" type="number" name="capacity" :value="old('capacity')" required min="1" placeholder="Contoh: 20" />
                  <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                </div>
              </div>

              {{-- Deskripsi --}}
              <div>
                <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
                <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="Tambahkan detail tentang kelas ini...">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
              </div>

              <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.classes_management') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                  Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm">
                  Simpan Kelas
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