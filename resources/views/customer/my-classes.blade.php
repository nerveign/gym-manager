<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Classes | FitAja</title>

  {{-- Fonts & Styles --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    * {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-100 font-sans antialiased">
  <div class="flex h-screen overflow-hidden">

    {{-- ================= SIDEBAR (Sesuai Dashboard) ================= --}}
    <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r flex flex-col">

      <x-dashboard-header name="{{ auth()->user()->name }}" />

      <nav class="mt-6 flex-1 overflow-y-auto">
        <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

        {{-- Dashboard Link --}}
        <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
        <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
        <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />

        {{-- My Classes (ACTIVE) --}}
        <x-nav-item text="My Classes" color="text-zinc-700" src="class.svg" location="customer.my-classes" style="bg-blue-50 border-r-4 border-blue-500" />

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
        <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />
        <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
      </nav>

      {{-- User Profile Section --}}
      <div class="p-4 border-t bg-white flex-shrink-0">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <a href="{{ route('profile.edit') }}">
              <img class="w-8 h-8 rounded-full object-cover border border-gray-200"
                src="{{ auth()->user()->image_url ?? asset('images/default-user.png') }}"
                alt="{{ auth()->user()->name }}">
            </a>
            <div class="ml-3 overflow-hidden">
              <p class="text-sm font-medium text-gray-700 truncate w-24">{{ auth()->user()->name }}</p>
              <p class="text-xs text-gray-500">Customer</p>
            </div>
          </div>
          <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
            @csrf
          </form>
          <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
            title="Logout">
            <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4">
          </button>
        </div>
      </div>
    </div>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="flex-1 ml-64 bg-gray-100 h-full overflow-hidden flex flex-col">
      <main class="flex-1 overflow-y-auto px-8 py-8 scroll-container">

        {{-- Header Page --}}
        <div class="mb-8 flex justify-between items-end">
          <div>
            <h2 class="text-2xl font-bold text-gray-900">My Classes</h2>
            <p class="text-gray-500 mt-1">Daftar kelas yang Anda ikuti beserta progressnya.</p>
          </div>

          <div class="flex gap-3">

            {{-- Tombol Cari Kelas --}}
            <a href="{{ route('customer.browse-classes') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 transition shadow-sm">
              <i class="fas fa-search mr-2"></i> Cari Kelas Baru
            </a>
          </div>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm flex items-start">
          <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3"></i>
          <div>
            <p class="text-sm font-bold text-green-800">Sukses!</p>
            <p class="text-sm text-green-700">{{ session('success') }}</p>
          </div>
        </div>
        @endif

        {{-- Grid Content --}}
        @if($myClasses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($myClasses as $class)
          <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 group">

            {{-- Card Header --}}
            <div class="flex justify-between items-start mb-4">
              <div>
                <h4 class="font-bold text-gray-900 text-lg group-hover:text-indigo-600 transition-colors">
                  {{ $class->gymClass->type ?? 'Class Name' }}
                </h4>
                <p class="text-sm text-gray-500 flex items-center mt-1">
                  <i class="fas fa-user-circle mr-1.5 text-gray-400"></i>
                  {{ $class->gymClass->trainer->name ?? 'Trainer' }}
                </p>
              </div>
              <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-md border border-indigo-100">
                {{ \Carbon\Carbon::parse($class->gymClass->schedule)->format('H:i') }}
              </span>
            </div>

            {{-- Date Info --}}
            <div class="flex items-center text-xs text-gray-500 mb-5 bg-gray-50 p-2 rounded-lg">
              <i class="far fa-calendar-alt mr-2 text-indigo-400"></i>
              {{ \Carbon\Carbon::parse($class->gymClass->schedule)->format('l, d F Y') }}
            </div>

            {{-- Progress Bar --}}
            @php
            // Pastikan method calculateProgress ada di Model ClassMember
            $prog = $class->calculateProgress();
            @endphp
            <div class="mb-5">
              <div class="flex justify-between text-xs mb-1.5">
                <span class="text-gray-600 font-medium">Progress Materi</span>
                <span class="font-bold {{ $prog >= 100 ? 'text-green-600' : 'text-indigo-600' }}">{{ $prog }}%</span>
              </div>
              <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="{{ $prog >= 100 ? 'bg-green-500' : 'bg-indigo-500' }} h-2 rounded-full transition-all duration-500"
                  style="width: {{ $prog }}%"></div>
              </div>
            </div>

            {{-- Footer Button --}}
            <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
              @if($prog >= 100)
              <span class="text-xs font-bold text-green-600 flex items-center">
                <i class="fas fa-medal mr-1"></i> LULUS
              </span>
              @else
              <span class="text-xs font-medium text-gray-400">On Going</span>
              @endif

              <a href="{{ route('customer.class.detail', $class->class_id) }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-indigo-600 text-indigo-600 rounded-lg font-medium text-xs uppercase tracking-wider hover:bg-indigo-50 transition shadow-sm">
                Detail
                <i class="fas fa-arrow-right ml-2"></i>
              </a>
            </div>
          </div>
          @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
          {{ $myClasses->links() }}
        </div>
        @else
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-300">
          <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-chalkboard-teacher text-gray-400 text-3xl"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada kelas</h3>
          <p class="text-gray-500 mb-6 text-center max-w-md">Anda belum mendaftar di kelas manapun. Yuk cari kelas yang cocok untukmu!</p>
          <a href="{{ route('customer.browse-classes') }}" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition shadow-sm">
            Mulai Cari Kelas
          </a>
        </div>
        @endif

      </main>
    </div>
  </div>
</body>

</html>