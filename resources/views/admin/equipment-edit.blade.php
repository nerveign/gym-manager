<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Edit Equipment</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    .swal2-popup {
      border-radius: 1rem !important;
      /* Rounded-xl */
      padding: 0 !important;
    }

    .swal2-actions {
      margin-top: 0 !important;
      padding-bottom: 1.5rem !important;
    }
  </style>
</head>

<body class="bg-gray-50 overflow-hidden">
  <div class="flex h-screen">
    <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
      <x-dashboard-header name="{{ $user->name }}" />

      <nav class="mt-6">
        <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
        <x-nav-item text="Dashboard" color="text-zinc-700" src="home.svg" location="admin.dashboard" />

        <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Manajemen Data</div>
        <x-nav-item text="Users" color="text-zinc-700" src="users.svg" location="admin.users_management" />
        <x-nav-item text="Trainers" color="text-gray-600" src="user.svg" location="admin.trainers_management" />
        <x-nav-item text="Bookings" color="text-gray-600" src="calendar.svg" location="admin.bookings_management" />
        <x-nav-item text="Classes" color="text-gray-600" src="class.svg" location="admin.classes_management" />
        <x-nav-item text="Equipment" color="text-gray-600" src="equipment.svg" location="admin.equipments_management" style="bg-blue-50 border-r-4 border-blue-500" />
        <x-nav-item text="Transactions" color="text-gray-600" src="dollar-sign.svg" location="admin.transactions_management" />
      </nav>

      <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white">
        <div class="flex items-center">
          <a href={{ route('profile.edit') }}>
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
            <p class="text-xs text-gray-500">Administrator</p>
          </div>
        </div>
      </div>
    </div>

    <div class="flex-1 ml-64 h-screen flex flex-col">
      <div class="bg-white border-b px-8 py-4 flex justify-between items-center shadow-sm shrink-0 z-20">
        <div class="flex items-center gap-4">
          <a href="{{ route('admin.equipments.show', $equipment->id) }}" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 hover:text-indigo-600 transition shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i>
          </a>
          <h1 class="text-xl font-bold text-gray-900">Edit Equipment</h1>
        </div>

        <form id="delete-form" action="{{ route('admin.equipments.destroy', $equipment->id) }}" method="POST">
          @csrf
          @method('DELETE')

          <button type="button" onclick="confirmDelete()" class="px-4 py-2 bg-red-50 text-red-500 border border-red-200 rounded-lg hover:bg-red-500 hover:text-white hover:border-red-500 transition shadow-sm text-sm font-medium flex items-center gap-2">
            <i class="fas fa-trash-alt"></i> Hapus
          </button>
        </form>
      </div>

      <div class="flex-1 overflow-y-auto p-8">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-8">

          <form action="{{ route('admin.equipments.update', $equipment->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Equipment</label>
                <input type="text" name="equipment_name" value="{{ old('equipment_name', $equipment->equipment_name) }}"
                  class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand / Merk</label>
                <input type="text" name="brand" value="{{ old('brand', $equipment->brand) }}"
                  class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                <select name="condition" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                  <option value="Baru" {{ $equipment->condition == 'Baru' ? 'selected' : '' }}>Baru</option>
                  <option value="Baik" {{ $equipment->condition == 'Baik' ? 'selected' : '' }}>Baik</option>
                  <option value="Sedang" {{ $equipment->condition == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                  <option value="Rusak" {{ $equipment->condition == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Stok</label>
                <input type="number" name="quantity" value="{{ old('quantity', $equipment->quantity) }}" min="0"
                  class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Update Gambar (Opsional)</label>

              @if($equipment->image_url)
              <div class="mb-3">
                <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                <img src="{{ $equipment->image_url }}" alt="Current Image" class="h-20 w-20 object-cover rounded-lg border border-gray-200">
              </div>
              @endif

              <input type="file" name="image" accept="image/*"
                class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2.5 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                                border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none">
              <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>

            <div class="mb-8">
              <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Lengkap</label>
              <textarea name="description" rows="5"
                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('description', $equipment->description) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-4 border-t pt-6">
              <a href="{{ route('admin.equipments.show', $equipment->id) }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Batal
              </a>
              <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm">
                Simpan Perubahan
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

  <script>
    function confirmDelete() {
      Swal.fire({
        // Menggunakan HTML Custom untuk kontrol penuh layout
        html: `
                    <div class="flex flex-col items-center pt-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Equipment?</h2>
                        <p class="text-sm text-gray-500 text-center px-4 mb-2">
                            Tindakan ini akan menghapus data <span class="font-bold text-gray-700">permanen</span>.
                        </p>
                        
                    </div>
                `,
        showCloseButton: false,
        showCancelButton: true,
        focusConfirm: false,

        // Text Tombol
        confirmButtonText: 'Ya, Hapus Data',
        cancelButtonText: 'Batalkan',

        // Matikan styling bawaan
        buttonsStyling: false,

        // Styling Tailwind untuk elemen popup
        customClass: {
          popup: 'rounded-2xl p-0 w-[24rem]', // Popup bulat dan lebar fixed
          actions: 'flex gap-3 justify-center w-full px-6 pb-6 mt-6', // Container tombol
          // Tombol Hapus menggunakan merah yang lebih soft
          confirmButton: 'w-full py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg text-sm transition shadow-sm',
          cancelButton: 'w-full py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg text-sm transition'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form').submit();
        }
      })
    }
  </script>
</body>

</html>