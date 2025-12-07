<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cari Kelas - FitAja</title>

  {{-- Fonts & Styles --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  {{-- SweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    * {
      font-family: 'Inter', sans-serif;
    }

    /* Custom Scrollbar */
    .scrollbar-thin::-webkit-scrollbar {
      width: 4px;
    }

    .scrollbar-thin::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
  </style>
</head>

<body class="bg-gray-100 font-sans antialiased">
  <div class="flex h-screen overflow-hidden">

    {{-- ================= SIDEBAR ================= --}}
    <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r flex flex-col">
      <x-dashboard-header name="{{ auth()->user()->name }}" />
      <nav class="mt-6 flex-1 overflow-y-auto">
        <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
        <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
        <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
        <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />
        <x-nav-item text="My Classes" color="text-zinc-700" src="class.svg" location="customer.my-classes" style="bg-blue-50 border-r-4 border-blue-500" />

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
        <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />
        <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
      </nav>

      {{-- User Profile --}}
      <div class="p-4 border-t bg-white flex-shrink-0">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <a href="{{ route('profile.edit') }}">
              <img class="w-8 h-8 rounded-full object-cover border border-gray-200" src="{{ auth()->user()->image_url ?? asset('images/default-user.png') }}" alt="{{ auth()->user()->name }}">
            </a>
            <div class="ml-3 overflow-hidden">
              <p class="text-sm font-medium text-gray-700 truncate w-24">{{ auth()->user()->name }}</p>
              <p class="text-xs text-gray-500">Customer</p>
            </div>
          </div>
          <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">@csrf</form>
          <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4">
          </button>
        </div>
      </div>
    </div>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="flex-1 ml-64 bg-gray-100 h-full overflow-hidden flex flex-col">
      <main class="flex-1 overflow-y-auto px-8 py-8 scroll-container">

        {{-- Header --}}
        <div class="mb-6 flex justify-between items-center">
          <div>
            <h2 class="text-2xl font-bold text-gray-900">Kelas Tersedia</h2>
            <p class="text-gray-600">Temukan dan daftar kelas baru untuk meningkatkan kebugaranmu.</p>
          </div>
          
        </div>

        {{-- Alert Error --}}
        @if(session('error'))
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
          <p>{{ session('error') }}</p>
        </div>
        @endif

        {{-- Grid Kelas Tersedia --}}
        @if($availableClasses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($availableClasses as $class)
          @php
          $isFull = $class->class_members_count >= $class->capacity;
          $slotsLeft = $class->capacity - $class->class_members_count;
          @endphp

          <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col h-full">
            {{-- Card Header --}}
            <div class="flex justify-between items-start mb-3">
              <div>
                <h4 class="font-bold text-gray-900 text-lg">{{ $class->type }}</h4>
                <p class="text-sm text-gray-600 flex items-center mt-1">
                  <i class="fas fa-user-tie mr-1.5 text-indigo-500"></i>
                  {{ $class->trainer->name }}
                </p>
              </div>
              <span class="px-2 py-1 text-xs font-bold rounded-md {{ $isFull ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                {{ $isFull ? 'FULL' : $slotsLeft . ' Slot' }}
              </span>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-2 gap-2 mb-4 text-xs text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
              <div class="flex items-center">
                <i class="far fa-calendar-alt mr-2 text-indigo-400"></i>
                {{ \Carbon\Carbon::parse($class->schedule)->format('d M') }}
              </div>
              <div class="flex items-center">
                <i class="far fa-clock mr-2 text-indigo-400"></i>
                {{ \Carbon\Carbon::parse($class->schedule)->format('H:i') }}
              </div>
              <div class="flex items-center col-span-2">
                <i class="fas fa-users mr-2 text-indigo-400"></i>
                {{ $class->class_members_count }} / {{ $class->capacity }} Peserta
              </div>
            </div>

            {{-- Agenda Preview --}}
            <div class="mb-4 flex-grow flex flex-col">
              <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 flex items-center">
                <i class="fas fa-list-ul mr-1.5 text-gray-400"></i> Agenda Lengkap
              </h5>
              <div class="flex-grow bg-gray-50 rounded-lg p-2 max-h-32 overflow-y-auto scrollbar-thin border border-gray-100">
                <ul class="text-sm text-gray-600 space-y-2">
                  @forelse($class->agendas as $agenda)
                  <li class="flex items-start">
                    <i class="fas fa-check-circle text-[10px] text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                    <span class="text-xs leading-snug">{{ $agenda->title }}</span>
                  </li>
                  @empty
                  <li class="text-xs text-gray-400 italic text-center py-2">Belum ada materi detail.</li>
                  @endforelse
                </ul>
              </div>
            </div>

            {{-- Action Button --}}
            <div class="mt-auto pt-3 border-t border-gray-100">
              @if($isFull)
              <button disabled class="w-full py-2 bg-gray-100 text-gray-400 font-bold rounded-lg cursor-not-allowed border border-gray-200 text-sm">
                Kelas Penuh
              </button>
              @else
              <form action="{{ route('customer.join-class', $class->id) }}" method="POST" onsubmit="return confirmJoin(event)">
                @csrf
                <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition shadow-md flex justify-center items-center group text-sm">
                  <span>Gabung Sekarang</span>
                  <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </button>
              </form>
              @endif
            </div>
          </div>
          @endforeach
        </div>

        <div class="mt-6">
          {{ $availableClasses->links() }}
        </div>
        @else
        <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-300">
          <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-search text-gray-400 text-2xl"></i>
          </div>
          <h3 class="text-gray-900 text-lg font-bold mb-2">Tidak ada kelas baru</h3>
          <p class="text-gray-600 mb-4">Saat ini belum ada kelas baru yang tersedia untuk diikuti.</p>
        </div>
        @endif

      </main>
    </div>
  </div>

  {{-- CUSTOM SWEETALERT SCRIPT --}}
  <script>
    function confirmJoin(event) {
      event.preventDefault(); // Mencegah submit langsung
      const form = event.target; // Mengambil elemen form

      Swal.fire({
        html: `
                    <div class="flex flex-col items-center pt-4">
                        {{-- Icon Custom (Warna Indigo karena positif) --}}
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-clipboard-check text-3xl text-indigo-600"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Gabung Kelas Ini?</h2>
                        <p class="text-sm text-gray-500 text-center px-4 mb-2">
                            Anda akan terdaftar sebagai peserta dan dapat melihat materi kelas.
                        </p>
                    </div>
                `,
        showCloseButton: false,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: 'Ya, Gabung Sekarang',
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        customClass: {
          popup: 'rounded-2xl p-0 w-[24rem]',
          actions: 'flex gap-3 justify-center w-full px-6 pb-6 mt-6',
          confirmButton: 'w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-sm transition shadow-sm',
          cancelButton: 'w-full py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg text-sm transition'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // Submit form jika user menekan Ya
        }
      });
    }
  </script>
</body>

</html>