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
        {{-- Sidebar --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name ?? auth()->user()->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
                <x-nav-item text="My Bookings" color="text-zinc-700" src="calendar.svg" location="customer.bookings.index" style="bg-blue-50 border-r-4 border-blue-500" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
                <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            {{-- User Profile Section --}}
            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        <img class="w-8 h-8 rounded-full object-cover"
                             src="{{ ($user->image_url ?? auth()->user()->image_url) ?? asset('images/default-user.png') }}"
                             alt="{{ $user->name ?? auth()->user()->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">
                            {{ $user->name ?? auth()->user()->name }}
                        </p>
                        <p class="text-xs text-gray-500">Customer</p>
                    </div>
                </div>
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center p-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 text-gray-500">
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 ml-64">
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">

                {{-- Header --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">My Bookings</h2>
                    <a href="{{ route('customer.bookings.create') }}"
                       class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-plus w-4 h-4"></i>
                        <span>New Booking</span>
                    </a>
                </div>

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Table --}}
                <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-sm text-gray-500 border-b bg-gray-50">
                                    <th class="px-6 py-3 font-medium">Trainer</th>
                                    <th class="px-6 py-3 font-medium">Date</th>
                                    <th class="px-6 py-3 font-medium">Time</th>
                                    <th class="px-6 py-3 font-medium">Duration</th>
                                    <th class="px-6 py-3 font-medium">Booked On</th>
                                    <th class="px-6 py-3 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($bookings as $booking)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        {{-- Trainer --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <img
                                                    src="{{ $booking->trainer->image_url ?? asset('images/default-user.png') }}"
                                                    alt="{{ $booking->trainer->name }}"
                                                    class="w-8 h-8 object-cover rounded-full border">
                                                <p class="font-medium text-gray-900">{{ $booking->trainer->name }}</p>
                                            </div>
                                        </td>
                                        {{-- Date --}}
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                                        </td>
                                        {{-- Time --}}
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ \Carbon\Carbon::parse($booking->time)->format('H:i') }}
                                        </td>
                                        {{-- Duration --}}
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $booking->duration }} mins
                                        </td>
                                        {{-- Booked On --}}
                                        <td class="px-6 py-4 text-gray-500 text-sm">
                                            {{ $booking->created_at ? $booking->created_at->format('d M Y, H:i') : 'N/A' }}
                                        </td>
                                        {{-- Actions --}}
                                        <td class="px-6 py-4">
                                            <button type="button"
                                                    onclick="confirmDelete('{{ route('customer.bookings.destroy', $booking->id) }}')"
                                                    class="text-red-600 hover:text-red-800 p-1 rounded-lg hover:bg-red-50 transition-colors duration-200"
                                                    title="Delete Booking">
                                                <i class="fas fa-trash-alt w-4 h-4"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-10 text-center text-gray-500">
                                            You haven't made any bookings yet.
                                            <a href="{{ route('customer.bookings.create') }}"
                                               class="text-blue-600 hover:underline ml-1">
                                                Book your first session!
                                            </a>
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
                        <x-pagination-footer
                            :firstItem="$bookings->firstItem()"
                            :lastItem="$bookings->lastItem()"
                            :total="$bookings->total()"
                            :onFirstPage="$bookings->onFirstPage()"
                            :hasMorePages="$bookings->hasMorePages()"
                            :previousPageUrl="$bookings->previousPageUrl()"
                            :nextPageUrl="$bookings->nextPageUrl()"
                            :lastPage="$bookings->lastPage()"
                            :currentPage="$bookings->currentPage()"
                            :pageUrl="fn($page) => $bookings->url($page)"
                        />
                    </div>
                @endif

            </main>
        </div>
    </div>

    {{-- Global delete form --}}
    <form id="delete-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- SweetAlert2 Delete Script --}}
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
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Bookings?</h2>
                        <p class="text-sm text-gray-500 text-center px-4 mb-2">
                            Tindakan ini akan menghapus data <span class="font-bold text-gray-700">permanen</span>.
                        </p>
                    </div>
                `,
                showCloseButton: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Ya, Hapus Data',
                cancelButtonText: 'Batalkan',
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
            });
        }
    </script>
</body>
</html>
