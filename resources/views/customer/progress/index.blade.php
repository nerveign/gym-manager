<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Progress Tracking | Customer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- 1. PENTING: CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- Fixed Sidebar (Sidebar) --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            {{-- Menggunakan auth() helper jika $user tidak di-pass --}}
            <x-dashboard-header name="{{ auth()->user()->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>

                {{-- Nav-Item untuk Home (INACTIVE) --}}
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>

                {{-- Nav-Item untuk Progress Tracking (ACTIVE) --}}
                <x-nav-item text="Progress Tracking" color="text-zinc-700" src="barbell.svg" location="customer.progress.index" style="bg-blue-50 border-r-4 border-blue-500" />

                {{-- Nav-Item untuk My Bookings (INACTIVE) --}}
                <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" /> 

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>

                {{-- Nav-Item Trainer List (INACTIVE) --}}
                <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />
                {{-- Nav-Item Equipment (INACTIVE) --}}
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            {{-- User Profile Section --}}
            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ auth()->user()->image_url ?? asset('images/default-user.png') }}" alt="{{ auth()->user()->name }}">
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

        {{-- Area Konten Utama (Main Content) --}}
        <div class="flex-1">
            <main class="ml-64 min-h-screen bg-gray-100 p-6">

                {{-- Header Halaman --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('My Progress Tracking') }}
                    </h2>
                    <a href="{{ route('customer.progress.create') }}"
                       class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Add Progress</span>
                    </a>
                </div>

                <div class="max-w-7xl mx-auto">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @isset($progress)
                        @if($progress->count() > 0)
                            <div class="flex flex-col gap-3" >
                                @foreach($progress as $item)
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                                        <div class="p-6">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-4 mb-3">
                                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-dumbbell text-xl text-blue-600"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="text-lg font-semibold text-gray-900">{{ $item->exercise }}</h3>
                                                            <span class="text-sm text-gray-500">
                                                                {{ $item->created_at ? $item->created_at->format('M j, Y') : 'N/A' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p class="text-gray-600 text-sm mb-4">
                                                        <strong>Duration:</strong> {{ $item->duration }} minutes
                                                    </p>
                                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $item->description ?? 'No description provided.' }}</p>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('customer.progress.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200" title="Edit">
                                                        <i class="fas fa-pencil-alt w-4 h-4"></i>
                                                    </a>
                                                    
                                                    {{-- 2. UBAH TOMBOL DELETE DI SINI --}}
                                                    {{-- Menggunakan type="button" dan onclick confirmDelete --}}
                                                    <button type="button" 
                                                            onclick="confirmDelete('{{ route('customer.progress.destroy', $item->id) }}')"
                                                            class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200" 
                                                            title="Delete">
                                                        <i class="fas fa-trash-alt w-4 h-4"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @else
                            {{-- Tampilan jika tidak ada progress --}}
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No progress records yet</h3>
                                    <p class="text-gray-500 mb-6">Start tracking your fitness journey by adding your first progress record.</p>
                                    <a href="{{ route('customer.progress.create') }}"
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        <span>Add First Progress</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6">
                           Error: Progress data could not be loaded.
                        </div>
                    @endisset
                </div>
            </main>
        </div>
    </div>

    {{-- 3. Form Hidden & Script SweetAlert (Sama seperti dashboard) --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

<script>
    function confirmDelete() {
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
          document.getElementById('delete-form').submit();
        }
      })
    }
  </script>
</body>

</html>