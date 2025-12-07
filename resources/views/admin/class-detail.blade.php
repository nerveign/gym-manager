<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'FitAja') }} - Detail Kelas</title>

  {{-- Fonts & Styles --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  {{-- SweetAlert2 --}}
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
            @if(auth()->user()->image_url)
            <img class="w-8 h-8 rounded-full object-cover border border-gray-200" src="{{ auth()->user()->image_url }}" alt="{{ auth()->user()->name }}">
            @else
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center border border-gray-300">
              <i class="fas fa-user text-gray-400 text-sm"></i>
            </div>
            @endif
          </a>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">Admin</p>
          </div>
        </div>
        <div>
          <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">@csrf</form>
          <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center justify-center w-8 h-8 ml-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
            <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 mr-1">
          </button>
        </div>
      </div>
    </div>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="flex-1 ml-64 bg-gray-100 h-screen overflow-hidden">
      <main class="pt-8 pb-8 px-8 h-full overflow-y-auto scroll-container">

        {{-- Breadcrumb & Header --}}
        <div class="mb-6">

          <div class="flex justify-between items-start mt-2">
            <div>
              <h1 class="text-3xl font-bold text-gray-900">{{ $gymClass->type }}</h1>
              <div class="flex items-center mt-2 text-sm text-gray-600">
                <span class="flex items-center mr-4">
                  <i class="fas fa-user-tie text-indigo-500 mr-2"></i>
                  {{ $gymClass->trainer->name }}
                </span>
                <span class="flex items-center">
                  <i class="far fa-calendar-alt text-indigo-500 mr-2"></i>
                  {{ \Carbon\Carbon::parse($gymClass->schedule)->format('d M Y, H:i') }}
                </span>
              </div>
            </div>
            <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-bold">
              {{ $gymClass->classMembers->count() }} / {{ $gymClass->capacity }} Siswa
            </span>
          </div>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
          <div class="flex">
            <div class="flex-shrink-0"><i class="fas fa-check-circle text-green-500"></i></div>
            <div class="ml-3">
              <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
          </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          {{-- KIRI: MANAJEMEN AGENDA --}}
          <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
              <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center pb-3 border-b border-gray-100">
                <i class="fas fa-list-check mr-2 text-indigo-600"></i> Agenda / Materi
              </h4>

              {{-- Form Tambah Materi --}}
              <div class="mb-6 bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                <h5 class="text-sm font-bold text-indigo-900 mb-3">Tambah Materi Baru</h5>
                <form action="{{ route('admin.class.agenda.store', $gymClass->id) }}" method="POST">
                  @csrf
                  <div class="space-y-3">
                    <div>
                      <input type="text" name="title" placeholder="Nama Materi (contoh: Warm Up)" required
                        class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    </div>
                    <div class="flex gap-2">
                      <input type="number" name="order" placeholder="Urutan" value="{{ $gymClass->agendas->count() + 1 }}"
                        class="w-1/3 text-sm border-gray-300 rounded-lg shadow-sm text-center">
                      <button type="submit" class="w-2/3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 px-4 rounded-lg transition-colors shadow-sm">
                        <i class="fas fa-plus mr-1"></i> Simpan
                      </button>
                    </div>
                  </div>
                </form>
              </div>

              {{-- List Agenda --}}
              <div class="flex-1 overflow-y-auto pr-1">
                <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Daftar Materi Saat Ini</h5>
                <div class="space-y-2">
                  @forelse($gymClass->agendas as $index => $agenda)
                  <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm group">
                    <div class="flex items-center overflow-hidden">
                      <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-xs font-bold mr-3 border border-gray-200">
                        {{ $agenda->order }}
                      </span>
                      <span class="text-gray-700 font-medium text-sm truncate" title="{{ $agenda->title }}">
                        {{ $agenda->title }}
                      </span>
                    </div>

                    {{-- Tombol Aksi (Edit & Delete) --}}
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                      {{-- Tombol Edit --}}
                      <button type="button"
                        onclick="openEditModal('{{ $agenda->id }}', '{{ addslashes($agenda->title) }}', '{{ $agenda->order }}')"
                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="Edit">
                        <i class="fas fa-pencil-alt text-xs"></i>
                      </button>

                      {{-- Tombol Hapus (Update untuk SweetAlert) --}}
                      <form id="delete-agenda-{{ $agenda->id }}" action="{{ route('admin.class.agenda.destroy', $agenda->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        {{-- Gunakan type="button" dan onclick event --}}
                        <button type="button" onclick="confirmDeleteAgenda({{ $agenda->id }})"
                          class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Hapus">
                          <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                      </form>
                    </div>
                  </div>
                  @empty
                  <div class="text-center py-8 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                    <i class="fas fa-clipboard-list text-gray-300 text-2xl mb-2"></i>
                    <p class="text-gray-500 text-xs italic">Belum ada materi.</p>
                  </div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>

          {{-- KANAN: PROGRESS SISWA --}}
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
              <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center pb-3 border-b border-gray-100">
                <i class="fas fa-users-cog mr-2 text-green-600"></i> Progress Kelulusan Siswa
              </h4>

              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Progress</th>
                      <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($gymClass->classMembers as $member)
                    <tr class="hover:bg-gray-50 transition-colors">
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
                            <span class="text-xs font-bold {{ $member->current_progress == 100 ? 'text-green-600' : 'text-indigo-600' }}">
                              {{ $member->current_progress }}%
                            </span>
                          </div>
                          <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="{{ $member->current_progress == 100 ? 'bg-green-500' : 'bg-indigo-500' }} h-2.5 rounded-full transition-all duration-500"
                              style="width: {{ $member->current_progress }}%"></div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($member->status == 'passed' || $member->current_progress >= 100)
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                          <i class="fas fa-check-circle mr-1.5 mt-0.5"></i> Lulus
                        </span>
                        @else
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                          <i class="fas fa-spinner fa-spin mr-1.5 mt-0.5"></i> On Going
                        </span>
                        @endif
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                          <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-user-slash text-gray-400 text-xl"></i>
                          </div>
                          <p class="text-sm font-medium">Belum ada siswa yang mendaftar.</p>
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

  {{-- ================= MODAL EDIT AGENDA ================= --}}
  <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
              <i class="fas fa-pencil-alt text-blue-600"></i>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Materi</h3>

              <form id="editForm" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PUT')

                <div>
                  <label class="block text-sm font-medium text-gray-700">Nama Materi</label>
                  <input type="text" id="edit_title" name="title" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Urutan</label>
                  <input type="number" id="edit_order" name="order" required min="1"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                  <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                    Simpan Perubahan
                  </button>
                  <button type="button" onclick="closeEditModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                    Batal
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- SCRIPTS --}}
  <script>
    // Modal Edit Script
    function openEditModal(id, title, order) {
      const form = document.getElementById('editForm');
      form.action = `/admin/dashboard/agenda/${id}`;
      document.getElementById('edit_title').value = title;
      document.getElementById('edit_order').value = order;
      document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
      document.getElementById('editModal').classList.add('hidden');
    }

    // SweetAlert Delete Script (Custom Design)
    function confirmDeleteAgenda(id) {
      Swal.fire({
        html: `
                    <div class="flex flex-col items-center pt-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Materi Ini?</h2>
                        <p class="text-sm text-gray-500 text-center px-4 mb-2">
                            Data yang dihapus <span class="font-bold text-gray-700">tidak dapat dikembalikan</span>.
                        </p>
                    </div>
                `,
        showCloseButton: false,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: 'Ya, Hapus',
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
          document.getElementById('delete-agenda-' + id).submit();
        }
      });
    }
  </script>
</body>

</html>