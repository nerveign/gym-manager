<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelas Saya - FitAja</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">

    {{-- ================= SIDEBAR TRAINER (Sama dengan Dashboard) ================= --}}
    <x-trainer-sidebar activeMenu="classes" />

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="flex-1 ml-64 bg-gray-100 h-screen overflow-hidden">
      <main class="pt-8 pb-8 px-8 h-full overflow-y-auto scroll-container">

        {{-- Header --}}
        <div class="mb-6 flex justify-between items-center">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelas Saya</h1>
            <p class="text-gray-500">Kelola materi dan progress siswa di kelas Anda.</p>
          </div>
        </div>

        {{-- Table Kelas --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                @forelse($classes as $class)
                <tr class="hover:bg-gray-50 transition">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-gray-900">{{ $class->type }}</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($class->schedule)->format('d M Y') }}</div>
                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($class->schedule)->format('H:i') }} WIB</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      {{ $class->class_members_count }} / {{ $class->capacity }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <a href="{{ route('trainer.class.detail', $class->id) }}" class="inline-flex items-center px-3 py-1.5 border border-indigo-600 text-indigo-600 rounded-md text-xs font-medium hover:bg-indigo-50 transition shadow-sm">
                      <i class="fas fa-eye mr-1.5"></i> Lihat Detail
                    </a>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                      <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-dumbbell text-gray-400 text-xl"></i>
                      </div>
                      <p class="text-sm font-medium">Belum ada kelas yang dibuat.</p>
                    </div>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- Pagination --}}
          <div class="px-6 py-4 border-t border-gray-200">
            {{ $classes->links() }}
          </div>
        </div>

      </main>
    </div>
  </div>
</body>

</html>