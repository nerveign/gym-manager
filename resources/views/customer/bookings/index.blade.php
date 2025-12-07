<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | Customer</title>
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
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
                {{-- Menu Active --}}
                <x-nav-item text="My Bookings" color="text-zinc-700" src="calendar.svg" location="customer.bookings.index" style="bg-blue-50 border-r-4 border-blue-500" />
                <x-nav-item text="My Classes" color="text-gray-600" src="class.svg" location="customer.my-classes" />

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

                {{-- 1. HEADER HALAMAN --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">My Bookings Management</h2>

                    {{-- Tombol New Booking (Biru) --}}
                    <a href="{{ route('customer.bookings.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm text-sm font-medium flex items-center gap-2">
                        <i class="fas fa-plus"></i> New Booking
                    </a>
                </div>

                {{-- 2. SEARCH BAR (SUDAH AKTIF) --}}
                <div class="mb-6">
                    <form method="GET" action="{{ route('customer.bookings.index') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search booking by trainer name..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Search
                        </button>

                        {{-- Tombol Clear (Muncul jika ada pencarian) --}}
                        @if(request('search'))
                        <a href="{{ route('customer.bookings.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium flex items-center">
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

                {{-- Alert Error --}}
                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
                @endif

                {{-- 3. TABEL DATA LIST --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <th class="px-6 py-3">TRAINER</th>
                                    <th class="px-6 py-3">SCHEDULE (DATE & TIME)</th>
                                    <th class="px-6 py-3">DURATION</th>
                                    <th class="px-6 py-3">BOOKED ON</th>
                                    <th class="px-6 py-3 text-right">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors duration-200 group">

                                    {{-- Kolom 1: Trainer --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($booking->trainer && $booking->trainer->image_url)
                                            <img class="w-10 h-10 rounded-full object-cover mr-3 border border-gray-200"
                                                src="{{ $booking->trainer->image_url }}"
                                                alt="{{ $booking->trainer->name }}">
                                            @else
                                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3 text-indigo-600 font-bold border border-indigo-200">
                                                {{ substr($booking->trainer->name ?? 'T', 0, 1) }}
                                            </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900 group-hover:text-blue-600 transition">
                                                    {{ $booking->trainer->name ?? 'Unknown Trainer' }}
                                                </p>
                                                <p class="text-xs text-gray-500">Personal Trainer</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom 2: Date & Time --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                at {{ \Carbon\Carbon::parse($booking->time)->format('H:i') }} WIB
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Kolom 3: Duration --}}
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                            <i class="far fa-clock mr-1"></i> {{ $booking->duration }} Mins
                                        </span>
                                    </td>

                                    {{-- Kolom 4: Booked On --}}
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $booking->created_at->format('d M Y') }}
                                        <span class="text-xs text-gray-400 block">{{ $booking->created_at->format('H:i') }}</span>
                                    </td>

                                    {{-- Kolom 5: Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <button type="button"
                                            onclick="confirmDelete('{{ route('customer.bookings.destroy', $booking->id) }}')"
                                            class="text-red-600 hover:text-red-800 font-medium text-sm transition flex items-center gap-1 justify-end ml-auto"
                                            title="Cancel Booking">
                                            <i class="fas fa-trash-alt"></i> Cancel
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                                <i class="fas fa-calendar-times text-2xl"></i>
                                            </div>
                                            <p class="font-medium text-gray-900">No bookings found</p>
                                            <p class="text-sm text-gray-500 mt-1 mb-4">
                                                @if(request('search'))
                                                No results for "{{ request('search') }}"
                                                @else
                                                You haven't scheduled any sessions yet.
                                                @endif
                                            </p>
                                            @if(!request('search'))
                                            <a href="{{ route('customer.bookings.create') }}" class="text-blue-600 hover:underline text-sm font-medium">
                                                Book your first session now
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if ($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->hasPages())
                <div class="mt-6">
                    {{ $bookings->links() }}
                </div>
                @endif

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
                html: `
                    <div class="flex flex-col items-center pt-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Batalkan Booking?</h2>
                        <p class="text-sm text-gray-500 text-center px-4 mb-2">
                            Tindakan ini akan menghapus jadwal booking Anda secara permanen.
                        </p>
                    </div>
                `,
                showCloseButton: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Kembali',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl p-0 w-[24rem]',
                    actions: 'flex gap-3 justify-center w-full px-6 pb-6 mt-6',
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