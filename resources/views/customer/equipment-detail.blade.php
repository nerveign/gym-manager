<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Equipment Detail | Customer</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 overflow-hidden">
  <div class="flex h-screen">
    {{-- Fixed Sidebar --}}
    <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
      <x-dashboard-header name="{{ $user->name }}" />

      <nav class="mt-6">
        <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

        {{-- Nav-Item untuk Home/Dashboard Customer --}}
        <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
        {{-- Nav-Item untuk Progress Tracking --}}
        <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />

        {{-- Nav-Item untuk My Bookings --}}
        <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />
        <x-nav-item text="My Classes" color="text-gray-600" src="class.svg" location="customer.my-classes" />

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
        {{-- Nav-Item Trainer List --}}
        <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />

        {{-- Nav-Item Equipment (ACTIVE) --}}
        <x-nav-item text="Equipment List" color="text-zinc-700" src="equipment.svg" location="customer.equipments.index" style="bg-blue-50 border-r-4 border-blue-500" />
      </nav>

      {{-- User Profile Section --}}
      <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
        <div class="flex items-center">
          <a href="{{ route('profile.edit') }}">
            @if($user->image_url)
            <img class="w-8 h-8 rounded-full object-cover" src="{{ $user->image_url }}" alt="{{ $user->name }}">
            @else
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
              <i class="fas fa-user text-gray-400 text-sm"></i>
            </div>
            @endif
          </a>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
            <p class="text-xs text-gray-500">Customer</p>
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

    {{-- Main Content Area --}}
    <div class="flex-1 ml-64 h-screen flex flex-col">

      {{-- Header with Back Button --}}
      <div class="bg-white border-b px-8 py-4 flex justify-between items-center shadow-sm shrink-0 z-20">
        <div class="flex items-center gap-4">
          <a href="{{ route('customer.equipments.index') }}" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 hover:text-indigo-600 transition shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i>
          </a>
          <div>
            <h1 class="text-xl font-bold text-gray-900 leading-tight">{{ $equipment->equipment_name }}</h1>
            <div class="flex items-center gap-2 text-xs text-gray-500">
              <span>#EQ-{{ str_pad($equipment->id, 4, '0', STR_PAD_LEFT) }}</span>
              <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
              <span>{{ $equipment->brand ?? 'No Brand' }}</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Content Grid --}}
      <div class="flex-1 min-h-0 p-6 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-full">

          {{-- Left Column: Image and Description --}}
          <div class="flex flex-col gap-6 h-full overflow-y-auto pr-2">

            {{-- Equipment Image --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[450px] shrink-0 overflow-hidden relative">
              <div class="absolute top-4 left-4 z-10">
                @php
                $conditionColor = match($equipment->condition) {
                'Baik', 'Baru' => 'bg-green-100 text-green-700 border-green-200',
                'Rusak' => 'bg-red-100 text-red-700 border-red-200',
                default => 'bg-yellow-100 text-yellow-700 border-yellow-200'
                };
                @endphp
                <span class="px-3 py-1 text-xs font-bold uppercase tracking-wide rounded-full border shadow-sm {{ $conditionColor }}">
                  {{ $equipment->condition }}
                </span>
              </div>

              <div class="w-full h-full p-12 flex items-center justify-center bg-white">
                @if($equipment->image_url)
                <img src="{{ $equipment->image_url }}"
                  alt="{{ $equipment->equipment_name }}"
                  class="w-full h-full object-contain">
                @else
                <div class="text-center text-gray-300">
                  <i class="fas fa-image text-6xl mb-3"></i>
                  <p>No Image</p>
                </div>
                @endif
              </div>
            </div>

            {{-- Description Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-fit shrink-0">
              <div class="px-6 py-3 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                  <i class="fas fa-align-left text-gray-400"></i> Deskripsi
                </h3>
              </div>
              <div class="p-6 text-sm text-gray-600 leading-relaxed text-justify">
                {{ $equipment->description ?? 'Tidak ada deskripsi detail tersedia untuk alat ini.' }}
              </div>
            </div>
          </div>

          {{-- Right Column: Information Status --}}
          <div class="h-full min-h-0 overflow-y-auto pb-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
              <h3 class="font-bold text-gray-800 mb-6 shrink-0">Informasi Status</h3>

              <div class="space-y-4 overflow-y-auto pr-2">
                {{-- Total Stock --}}
                <div class="flex items-center p-4 bg-blue-50 rounded-xl border border-blue-100">
                  <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-3 shrink-0">
                    <i class="fas fa-boxes"></i>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 font-medium">Total Stok</p>
                    <p class="text-xl font-bold text-gray-900">{{ $equipment->quantity }} <span class="text-xs font-normal text-gray-500">Unit</span></p>
                  </div>
                </div>

                {{-- Brand --}}
                <div class="flex items-center p-4 bg-purple-50 rounded-xl border border-purple-100">
                  <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mr-3 shrink-0">
                    <i class="fas fa-tag"></i>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 font-medium">Merk / Brand</p>
                    <p class="text-base font-bold text-gray-900">{{ $equipment->brand ?? '-' }}</p>
                  </div>
                </div>

                {{-- Last Updated --}}
                <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                  <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 mr-3 shrink-0">
                    <i class="far fa-clock"></i>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 font-medium">Update Terakhir</p>
                    <p class="text-sm font-bold text-gray-900">
                      {{ $equipment->updated_at ? $equipment->updated_at->format('d M Y') : '-' }}
                    </p>
                    <p class="text-[10px] text-gray-400">
                      {{ $equipment->updated_at ? $equipment->updated_at->format('H:i') . ' WIB' : '' }}
                    </p>
                  </div>
                </div>

                {{-- Created Date --}}
                <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                  <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 mr-3 shrink-0">
                    <i class="far fa-calendar-plus"></i>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 font-medium">Tanggal Dibuat</p>
                    <p class="text-sm font-bold text-gray-900">
                      {{ $equipment->created_at ? $equipment->created_at->format('d M Y') : '-' }}
                    </p>
                  </div>
                </div>
              </div>

              <div class="flex-1"></div>

              {{-- Equipment ID Footer --}}
              <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400">Equipment ID: #{{ $equipment->id }}</p>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</body>

</html>