<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Kelas - {{ $gymClass->type }}</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">

    {{-- FIXED SIDEBAR (Sama persis dengan Dashboard) --}}
    <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r flex flex-col">

      <x-dashboard-header name="{{ auth()->user()->name }}" />

      {{-- Navigation Menu --}}
      <nav class="mt-6 flex-1 overflow-y-auto">
        <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

        {{-- Dashboard Link (Inactive) --}}
        <a href="{{ route('trainer.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
          <img src="{{ asset('icons/home.svg') }}" alt="home" class="w-5 h-5 mr-3 text-gray-500">
          <span class="font-medium">Dashboard</span>
        </a>

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-4">Aktivitas Saya</div>

        {{-- Booking Link (Inactive) --}}
        <a href="{{ route('trainer.bookings') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
          <img src="{{ asset('icons/calendar.svg') }}" alt="booking" class="w-5 h-5 mr-3 text-gray-500">
          <span>My Booking</span>
        </a>

        {{-- Kelas Saya Link (ACTIVE STATE) --}}
        <a href="{{ route('trainer.classes') }}" class="flex items-center px-4 py-3 bg-blue-50 text-blue-600 border-r-4 border-blue-600 transition-colors">
          <img src="{{ asset('icons/class.svg') }}" alt="class" class="w-5 h-5 mr-3 text-blue-600">
          <span class="font-medium">My Classes</span>
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

    {{-- MAIN CONTENT AREA --}}
    <div class="flex-1 ml-64 bg-gray-100 h-screen overflow-hidden">
      <main class="pt-8 pb-8 px-8 h-full overflow-y-auto scroll-container">

        {{-- Breadcrumb / Header --}}
        <div class="mb-6">

          <div class="flex items-center justify-between mt-2">
            <div>
              <h1 class="text-3xl font-bold text-gray-900">{{ $gymClass->type }}</h1>
              <p class="text-gray-500 flex items-center mt-1">
                <i class="far fa-clock mr-2"></i>
                {{ \Carbon\Carbon::parse($gymClass->schedule)->format('d M Y, H:i') }} WIB
              </p>
            </div>
            <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-bold">
              {{ $gymClass->classMembers->count() }} / {{ $gymClass->capacity }} Siswa
            </span>
          </div>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
          {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          {{-- Informasi Materi --}}
          <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full">
              <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-list-ul mr-2 text-indigo-500"></i> Materi Kelas
              </h3>
              <ul class="space-y-3">
                @forelse($gymClass->agendas as $index => $agenda)
                <li class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                  <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold mr-3 flex-shrink-0">
                    {{ $index + 1 }}
                  </span>
                  <span class="text-sm text-gray-700 font-medium">{{ $agenda->title }}</span>
                </li>
                @empty
                <li class="text-center text-gray-400 text-sm italic py-8 border border-dashed rounded-lg">
                  Belum ada materi yang diatur oleh Admin.
                </li>
                @endforelse
              </ul>
            </div>
          </div>

          {{-- Daftar Siswa --}}
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full">
              <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-users mr-2 text-indigo-500"></i> Daftar Siswa & Progress
              </h3>

              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                      <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100">
                    @forelse($gymClass->classMembers as $member)
                    <tr class="hover:bg-gray-50 transition">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm mr-3">
                            {{ substr($member->user->name, 0, 1) }}
                          </div>
                          <div>
                            <div class="text-sm font-bold text-gray-900">{{ $member->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $member->user->email }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap align-middle">
                        <div class="w-full max-w-xs">
                          <div class="flex justify-between mb-1">
                            <span class="text-xs font-medium {{ $member->current_progress == 100 ? 'text-green-700' : 'text-blue-700' }}">
                              {{ $member->current_progress }}%
                            </span>
                            @if($member->status == 'passed')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">LULUS</span>
                            @endif
                          </div>
                          <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="{{ $member->current_progress == 100 ? 'bg-green-500' : 'bg-blue-500' }} h-2 rounded-full transition-all duration-500"
                              style="width: {{ $member->current_progress }}%"></div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right">
                        <a href="{{ route('trainer.student.progress', ['classId' => $gymClass->id, 'userId' => $member->user_id]) }}"
                          class="inline-flex items-center px-3 py-1.5 bg-white border border-indigo-600 text-indigo-600 text-xs font-medium rounded hover:bg-indigo-50 transition">
                          <i class="fas fa-check-square mr-1.5"></i> Update
                        </a>
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                          <i class="fas fa-user-slash text-gray-300 text-3xl mb-2"></i>
                          <span class="italic text-sm">Belum ada siswa yang mendaftar di kelas ini.</span>
                        </div>
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </main>
    </div>
  </div>
</body>

</html>