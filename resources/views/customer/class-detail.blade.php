<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Kelas - {{ $gymClass->type }}</title>

  {{-- GOOGLE FONT INTER --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    * {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">

    {{-- ================= SIDEBAR (Sama dengan Dashboard) ================= --}}
    <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
      <x-dashboard-header name="{{ auth()->user()->name }}" />

      <nav class="mt-6">
        <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
        {{-- Link Dashboard (Inactive) --}}
        <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
        <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
        <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />

        {{-- Link My Classes (ACTIVE) --}}
        <x-nav-item text="My Classes" color="text-zinc-700" src="class.svg" location="customer.my-classes" style="bg-blue-50 border-r-4 border-blue-500" />

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
        <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />
        <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
      </nav>

      {{-- User Profile di Bawah Sidebar --}}
      <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
        <div class="flex items-center">
          <a href="{{ route('profile.edit') }}">
            <img class="w-8 h-8 rounded-full object-cover" src="{{ auth()->user()->image_url ?? asset('images/default-user.png') }}" alt="{{ auth()->user()->name }}">
          </a>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-700">{{ explode(' ', auth()->user()->name)[0] }}</p>
            <p class="text-xs text-gray-500">Customer</p>
          </div>
        </div>
        <div>
          <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
            @csrf
          </form>
          <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
            <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4">
          </button>
        </div>
      </div>
    </div>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="flex-1 ml-64">
      <main class="pt-6 pb-8 px-6 h-screen overflow-y-auto scroll-container">

        {{-- Breadcrumb / Header --}}
        <div class="mb-6">

          <div class="flex justify-between items-start mt-2">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">{{ $gymClass->type }}</h2>
              <p class="text-gray-600 flex items-center mt-1">
                <i class="fas fa-user-circle mr-2 text-gray-400"></i>
                Trainer: <span class="font-medium text-indigo-600 ml-1">{{ $gymClass->trainer->name }}</span>
              </p>
            </div>

            {{-- Badge Status --}}
            @if($classMember->status == 'passed' || $progress >= 100)
            <div class="flex items-center bg-green-100 text-green-700 px-4 py-2 rounded-lg border border-green-200">
              <i class="fas fa-check-circle mr-2 text-lg"></i>
              <div>
                <p class="text-xs font-bold uppercase">Status</p>
                <p class="font-bold text-sm">LULUS</p>
              </div>
            </div>
            @else
            <div class="flex items-center bg-blue-50 text-blue-700 px-4 py-2 rounded-lg border border-blue-200">
              <i class="fas fa-spinner fa-spin mr-2 text-lg"></i>
              <div>
                <p class="text-xs font-bold uppercase">Status</p>
                <p class="font-bold text-sm">ON GOING</p>
              </div>
            </div>
            @endif
          </div>
        </div>

        {{-- Grid Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          {{-- Left Column: Info & Progress Bar --}}
          <div class="lg:col-span-2 space-y-6">

            {{-- Card Progress --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
              <h3 class="text-lg font-bold text-gray-900 mb-4">Progress Kelulusan</h3>

              <div class="mb-2 flex justify-between items-end">
                <span class="text-sm text-gray-600">Pencapaian Materi</span>
                <span class="text-2xl font-extrabold {{ $progress >= 100 ? 'text-green-600' : 'text-indigo-600' }}">
                  {{ $progress }}%
                </span>
              </div>

              <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden">
                <div class="{{ $progress >= 100 ? 'bg-green-500' : 'bg-indigo-600' }} h-4 rounded-full transition-all duration-1000 ease-out relative" style="width: {{ $progress }}%">
                  <div class="absolute inset-0 bg-white/20 animate-[pulse_2s_infinite]"></div>
                </div>
              </div>

              <p class="text-xs text-gray-500 mt-3 text-center">
                @if($progress >= 100)
                Selamat! Anda telah menyelesaikan seluruh materi di kelas ini.
                @else
                Selesaikan semua materi checklist bersama Trainer untuk mencapai kelulusan.
                @endif
              </p>
            </div>

            {{-- Card Daftar Materi --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
              <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-list-check mr-2 text-gray-400"></i>
                Daftar Materi (Checklist)
              </h3>

              <div class="space-y-3">
                @forelse($gymClass->agendas as $index => $agenda)
                @php
                $isCompleted = in_array($agenda->id, $completedAgendaIds);
                @endphp

                <div class="flex items-center p-4 rounded-xl border transition-colors {{ $isCompleted ? 'bg-green-50 border-green-200' : 'bg-white border-gray-100' }}">
                  {{-- Icon Status --}}
                  <div class="flex-shrink-0 mr-4">
                    @if($isCompleted)
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white shadow-sm">
                      <i class="fas fa-check text-sm"></i>
                    </div>
                    @else
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200 font-bold text-xs">
                      {{ $index + 1 }}
                    </div>
                    @endif
                  </div>

                  {{-- Text Materi --}}
                  <div class="flex-1">
                    <h4 class="text-sm font-bold {{ $isCompleted ? 'text-green-800' : 'text-gray-700' }}">
                      {{ $agenda->title }}
                    </h4>
                    <p class="text-xs {{ $isCompleted ? 'text-green-600' : 'text-gray-500' }}">
                      {{ $isCompleted ? 'Verified by Trainer' : 'Belum diselesaikan' }}
                    </p>
                  </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400 italic bg-gray-50 rounded-xl border border-dashed border-gray-200">
                  Belum ada materi yang ditambahkan di kelas ini.
                </div>
                @endforelse
              </div>
            </div>
          </div>

          {{-- Right Column: Class Info --}}
          <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 h-fit sticky top-6">
              <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Kelas</h3>

              <div class="space-y-4">
                <div class="flex items-start">
                  <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 mr-3 flex-shrink-0">
                    <i class="far fa-calendar-alt"></i>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">Jadwal</p>
                    <p class="text-sm font-semibold text-gray-900">
                      {{ \Carbon\Carbon::parse($gymClass->schedule)->format('l, d F Y') }}
                    </p>
                    <p class="text-xs text-gray-600">
                      {{ \Carbon\Carbon::parse($gymClass->schedule)->format('H:i') }} WIB
                    </p>
                  </div>
                </div>

                <div class="flex items-start">
                  <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 mr-3 flex-shrink-0">
                    <i class="fas fa-dumbbell"></i>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">Kapasitas</p>
                    <p class="text-sm font-semibold text-gray-900">
                      {{ $gymClass->classMembers->count() }} / {{ $gymClass->capacity }} Peserta
                    </p>
                  </div>
                </div>

                <div class="border-t pt-4 mt-2">
                  <p class="text-xs text-gray-500 uppercase font-bold mb-2">Deskripsi</p>
                  <p class="text-sm text-gray-600 leading-relaxed">
                    {{ $gymClass->description ?? 'Tidak ada deskripsi khusus untuk kelas ini.' }}
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