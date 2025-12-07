<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Kelas</title>

  {{-- Font & Styles --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">

    {{-- ================= SIDEBAR ================= --}}
    <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
      <x-dashboard-header name="{{ auth()->user()->name }}" />

      <nav class="mt-6">
        <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
        <x-nav-item text="Dashboard" color="text-zinc-700" src="home.svg" location="admin.dashboard" />

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Manajemen Data</div>
        <x-nav-item text="Users" color="text-zinc-700" src="users.svg" location="admin.users_management" />
        <x-nav-item text="Trainers" color="text-zinc-700" src="user.svg" location="admin.trainers_management" />

        <x-nav-item text="Classes" color="text-zinc-700" src="class.svg" location="admin.classes_management" style="bg-blue-50 border-r-4 border-blue-500" />

        <x-nav-item text="Bookings" color="text-zinc-700" src="calendar.svg" location="admin.bookings_management" />
        <x-nav-item text="Equipment" color="text-zinc-700" src="equipment.svg" location="admin.equipments_management" />
        <x-nav-item text="Transactions" color="text-zinc-700" src="dollar-sign.svg" location="admin.transactions_management" />
      </nav>

      <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
        <div class="flex items-center">
          <a href="{{ route('profile.edit') }}">
            <img class="w-8 h-8 rounded-full object-cover" src="{{ auth()->user()->image_url ?? asset('images/default-user.png') }}" alt="{{ auth()->user()->name }}">
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

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="flex-1 ml-64 bg-gray-100 h-screen overflow-hidden">
      <main class="pt-8 pb-8 px-8 h-full overflow-y-auto scroll-container">

        <div class="max-w-3xl mx-auto">
          <div class="mb-6 flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800">Edit Kelas</h2>

          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-8 border border-gray-100">

            {{-- FORM UPDATE --}}
            <form action="{{ route('admin.classes.update', $gymClass->id) }}" method="POST" class="space-y-6">
              @csrf
              @method('PUT')

              {{-- Nama Kelas --}}
              <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas / Tipe</label>
                <input id="type" type="text" name="type" value="{{ old('type', $gymClass->type) }}" required
                  class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150 ease-in-out">
                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
              </div>

              {{-- Trainer --}}
              <div>
                <label for="trainer_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Trainer</label>
                <select id="trainer_id" name="trainer_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150 ease-in-out">
                  @foreach($trainers as $trainer)
                  <option value="{{ $trainer->id }}" {{ old('trainer_id', $gymClass->trainer_id) == $trainer->id ? 'selected' : '' }}>
                    {{ $trainer->name }}
                  </option>
                  @endforeach
                </select>
                @error('trainer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
              </div>

              <div class="grid grid-cols-2 gap-6">
                {{-- Jadwal --}}
                <div>
                  <label for="schedule" class="block text-sm font-medium text-gray-700 mb-1">Jadwal Kelas</label>
                  <input id="schedule" type="datetime-local" name="schedule" value="{{ old('schedule', $gymClass->schedule) }}" required
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150 ease-in-out">
                  @error('schedule') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kapasitas --}}
                <div>
                  <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Maksimal</label>
                  <input id="capacity" type="number" name="capacity" value="{{ old('capacity', $gymClass->capacity) }}" required min="1"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150 ease-in-out">
                  @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
              </div>

              {{-- Deskripsi --}}
              <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                <textarea id="description" name="description" rows="3"
                  class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150 ease-in-out">{{ old('description', $gymClass->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
              </div>

              <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all ml-auto">
                  Simpan Perubahan
                </button>
              </div>
            </form>

            {{-- AREA HAPUS KELAS --}}
            <div class="mt-8 pt-6 border-t border-gray-100">
              <div class="flex justify-between items-center bg-red-50 p-4 rounded-lg border border-red-100">
                <div>
                  <h3 class="text-sm font-bold text-red-800">Hapus Kelas Ini</h3>
                  <p class="text-xs text-red-600 mt-1">Tindakan ini tidak dapat dibatalkan. Semua data booking dan materi akan terhapus.</p>
                </div>

                {{-- Form Delete dengan ID khusus --}}
                <form id="delete-form" action="{{ route('admin.classes.destroy', $gymClass->id) }}" method="POST">
                  @csrf
                  @method('DELETE')

                  {{-- Tombol Trigger SweetAlert --}}
                  <button type="button" onclick="confirmDelete()"
                    class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition shadow-sm flex items-center">
                    <i class="fas fa-trash-alt mr-1.5"></i> Hapus Permanen
                  </button>
                </form>
              </div>
            </div>

          </div>
        </div>
      </main>
    </div>
  </div>

  {{-- SCRIPT SWEETALERT2 CUSTOM --}}
  <script>
    function confirmDelete() {
      Swal.fire({
        html: `
                    <div class="flex flex-col items-center pt-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Kelas Ini?</h2>
                        <p class="text-sm text-gray-500 text-center px-4 mb-2">
                            Data yang dihapus <span class="font-bold text-gray-700">tidak dapat dikembalikan</span>. Pastikan ini tindakan yang benar.
                        </p>
                    </div>
                `,
        showCloseButton: false,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: 'Ya, Hapus Sekarang',
        cancelButtonText: 'Batalkan',
        buttonsStyling: false,
        customClass: {
          popup: 'rounded-2xl p-0 w-[24rem]',
          actions: 'flex gap-3 justify-center w-full px-6 pb-6 mt-6',
          confirmButton: 'w-full py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg text-sm transition shadow-sm',
          cancelButton: 'w-full py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg text-sm transition'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Submit form jika user klik "Ya, Hapus"
          document.getElementById('delete-form').submit();
        }
      });
    }
  </script>
</body>

</html>