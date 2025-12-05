<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Progress Tracking | Customer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        
        {{-- ================= SIDEBAR ================= --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ auth()->user()->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                {{-- Menu Active --}}
                <x-nav-item text="Progress Tracking" color="text-zinc-700" src="barbell.svg" location="customer.progress.index" style="bg-blue-50 border-r-4 border-blue-500" />
                <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
                <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        <img class="w-8 h-8 rounded-full object-cover" 
                             src="{{ auth()->user()->image_url ?? asset('images/default-user.png') }}" 
                             alt="{{ auth()->user()->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Customer</p>
                    </div>
                </div>
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center p-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 text-gray-500">
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="flex-1 ml-64">
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">
                
                {{-- 1. HEADER HALAMAN (Sesuai Referensi) --}}
                <div class="flex justify-between items-center mb-6">
                    {{-- Judul di Kiri --}}
                    <h2 class="text-2xl font-bold text-gray-900">Progress Tracking Management</h2>
                    
                    {{-- Tombol Tambah di Kanan (Biru) --}}
                    <a href="{{ route('customer.progress.create') }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm text-sm font-medium flex items-center gap-2">
                        <i class="fas fa-plus"></i> Tambah Progress
                    </a>
                </div>

                {{-- 2. SEARCH BAR FULL WIDTH (Sesuai Referensi) --}}
                <div class="mb-6">
                    <form method="GET" action="{{ route('customer.progress.index') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search progress by exercise name or description..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('customer.progress.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium flex items-center">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Alert Sukses --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- 3. TABEL DATA LIST (Menggantikan Card View) --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                {{-- Header Tabel --}}
                                <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <th class="px-6 py-3">LATIHAN / EXERCISE</th>
                                    <th class="px-6 py-3">DURATION</th>
                                    <th class="px-6 py-3">DATE RECORDED</th>
                                    <th class="px-6 py-3 text-right">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($progress as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                    
                                    {{-- Kolom 1: Exercise (Mirip style kolom Equipment Name) --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            {{-- Icon visual pengganti gambar produk --}}
                                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4 text-gray-500 group-hover:bg-blue-100 group-hover:text-blue-600 transition shadow-sm">
                                                <i class="fas fa-dumbbell"></i>
                                            </div>
                                            <div>
                                                {{-- Nama Latihan --}}
                                                <p class="font-medium text-gray-900 group-hover:text-blue-600 transition">{{ $item->exercise }}</p>
                                                {{-- Deskripsi singkat dibawah nama --}}
                                                <p class="text-sm text-gray-500">{{ Str::limit($item->description, 60) ?? 'No notes' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- Kolom 2: Duration (Mirip style kolom Brand/Condition) --}}
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                            {{ $item->duration }} Menit
                                        </span>
                                    </td>

                                    {{-- Kolom 3: Date (Mirip style kolom Last Updated) --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if($item->created_at)
                                            {{ $item->created_at->format('d M Y') }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    {{-- Kolom 4: Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('customer.progress.edit', $item->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-medium text-sm transition flex items-center gap-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            {{-- Tombol Delete --}}
                                            <button type="button" 
                                                    onclick="confirmDelete('{{ route('customer.progress.destroy', $item->id) }}')"
                                                    class="text-red-600 hover:text-red-800 font-medium text-sm transition flex items-center gap-1">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                                <i class="fas fa-clipboard-list text-2xl"></i>
                                            </div>
                                            <p class="font-medium text-gray-900">No progress data found</p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                @if(request('search'))
                                                    No results for "{{ request('search') }}"
                                                @else
                                                    Start tracking your fitness journey today!
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    {{-- Form Delete Global --}}
    <form id="delete-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete(url) {
            const form = document.getElementById('delete-form');
            form.action = url;
            Swal.fire({
                // Menggunakan HTML Custom untuk kontrol penuh layout
                html: `
                    <div class="flex flex-col items-center pt-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Progress Tracking?</h2>
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
                    form.submit();
                }
            })
        }
    </script>
</body>
</html>