<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Update Progress - {{ $studentMember->user->name }}</title>

  {{-- Fonts & Styles --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
  <div class="flex h-screen">

    {{-- ================= SIDEBAR TRAINER (Sama dengan Dashboard) ================= --}}
    <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r flex flex-col">

      <x-dashboard-header name="{{ auth()->user()->name }}" />

      {{-- Navigation Menu --}}
      <nav class="mt-6 flex-1 overflow-y-auto">
        <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

        {{-- Dashboard Link --}}
        <a href="{{ route('trainer.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
          <img src="{{ asset('icons/home.svg') }}" alt="home" class="w-5 h-5 mr-3 text-gray-500">
          <span class="font-medium">Dashboard</span>
        </a>

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-4">Aktivitas Saya</div>

        {{-- Booking Link --}}
        <a href="{{ route('trainer.bookings') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
          <img src="{{ asset('icons/calendar.svg') }}" alt="booking" class="w-5 h-5 mr-3 text-gray-500">
          <span>Booking Saya</span>
        </a>

        {{-- Kelas Saya Link (ACTIVE STATE) --}}
        <a href="{{ route('trainer.classes') }}" class="flex items-center px-4 py-3 bg-blue-50 text-blue-600 border-r-4 border-blue-600 transition-colors">
          <img src="{{ asset('icons/class.svg') }}" alt="class" class="w-5 h-5 mr-3 text-blue-600">
          <span class="font-medium">Kelas Saya</span>
        </a>
      </nav>

      {{-- User Profile Section --}}
      <div class="p-4 border-t bg-white flex-shrink-0">
        <div class="flex items-center justify-between">
          <a href="{{ route('trainer.profile.edit') }}" class="flex items-center flex-1 hover:bg-gray-50 rounded-lg p-2 transition-colors group">
            @if(auth()->user()->image_url)
            <img class="w-8 h-8 rounded-full object-cover border border-gray-200"
              src="{{ auth()->user()->image_url }}"
              alt="{{ auth()->user()->name }}">
            @else
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center border border-gray-300">
              <i class="fas fa-user text-gray-400 text-xs"></i>
            </div>
            @endif
            <div class="ml-3 overflow-hidden">
              <p class="text-sm font-medium text-gray-700 truncate group-hover:text-indigo-600 transition-colors">{{ auth()->user()->name }}</p>
              <p class="text-xs text-gray-500 capitalize">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
          </a>

          <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
            @csrf
          </form>
          <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center justify-center w-8 h-8 ml-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
            title="Logout">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </div>
      </div>
    </div>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="flex-1 ml-64 bg-gray-100 h-screen overflow-hidden">
      <main class="pt-8 pb-8 px-8 h-full overflow-y-auto scroll-container">

        {{-- Header & Breadcrumb --}}
        <div class="max-w-2xl mx-auto mb-6">

          <h1 class="text-2xl font-bold text-gray-900 mt-1">Update Progress Siswa</h1>
          <p class="text-gray-500 text-sm">Centang materi yang telah diselesaikan oleh siswa.</p>
        </div>

        {{-- Form Container --}}
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-6">

          {{-- Info Siswa --}}
          <div class="flex items-center p-4 bg-indigo-50 rounded-lg border border-indigo-100 mb-6">
            <div class="h-12 w-12 rounded-full bg-indigo-100 border-2 border-white shadow-sm flex items-center justify-center text-indigo-600 font-bold text-lg mr-4">
              {{ substr($studentMember->user->name, 0, 1) }}
            </div>
            <div>
              <p class="text-base font-bold text-gray-900">{{ $studentMember->user->name }}</p>
              <p class="text-xs text-gray-500">Kelas: {{ $gymClass->type }}</p>
            </div>
          </div>

          <form action="{{ route('trainer.student.progress.update', ['classId' => $gymClass->id, 'userId' => $studentMember->user_id]) }}" method="POST">
            @csrf

            <div class="space-y-3 mb-8">
              <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">Daftar Materi (Checklist)</h3>

              @forelse($agendas as $agenda)
              <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-indigo-200 cursor-pointer transition-all group">
                <div class="flex items-center h-5">
                  <input type="checkbox"
                    name="completed_agendas[]"
                    value="{{ $agenda->id }}"
                    class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 transition duration-150 ease-in-out"
                    {{ in_array($agenda->id, $completedAgendaIds) ? 'checked' : '' }}>
                </div>
                <div class="ml-3 text-sm">
                  <span class="font-medium text-gray-700 group-hover:text-indigo-700 transition-colors">{{ $agenda->title }}</span>
                </div>
              </label>
              @empty
              <div class="text-center text-gray-500 py-8 border-2 border-dashed border-gray-200 rounded-lg">
                <i class="fas fa-clipboard-list text-2xl text-gray-300 mb-2"></i>
                <p class="text-sm">Tidak ada materi checklist tersedia.</p>
                <p class="text-xs text-gray-400">Hubungi Admin untuk menambahkan materi.</p>
              </div>
              @endforelse
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
              <a href="{{ route('trainer.class.detail', $gymClass->id) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                Batal
              </a>
              <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all">
                Simpan Perubahan
              </button>
            </div>
          </form>
        </div>

      </main>
    </div>
  </div>
</body>

</html>