<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Tambah Equipment</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
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
          <a href="{{ route('admin.equipments_management') }}" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 hover:text-indigo-600 transition shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i>
          </a>
          <h1 class="text-xl font-bold text-gray-900">Tambah Equipment Baru</h1>
        </div>
      </div>

      <div class="flex-1 overflow-y-auto p-8">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-8">

          <form action="{{ route('admin.equipments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Equipment</label>
                <input type="text" name="equipment_name" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Contoh: Treadmill X1" required>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand / Merk</label>
                <input type="text" name="brand" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Contoh: Technogym" required>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                <select name="condition" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                  <option value="Baru">Baru</option>
                  <option value="Baik">Baik</option>
                  <option value="Sedang">Sedang</option>
                  <option value="Rusak">Rusak</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Stok</label>
                <input type="number" name="quantity" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="0" min="0" required>
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar Equipment</label>
              <input type="file" name="image" accept="image/*"
                class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2.5 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                                border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none">
              <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF (Max. 2MB)</p>
            </div>

            <div class="mb-8">
              <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Lengkap</label>
              <textarea name="description" rows="5" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Tuliskan deskripsi alat..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-4 border-t pt-6">
              <a href="{{ route('admin.equipments_management') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Batal
              </a>
              <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm">
                Simpan Data
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</body>

</html>