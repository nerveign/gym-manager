<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Booking List</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                <x-nav-item text="Dashboard" color="text-zinc-700" src="home.svg" location="admin.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Manajemen Data</div>
                <x-nav-item text="Users" color="text-zinc-700" src="users.svg" location="admin.users_management" />
                <x-nav-item text="Trainers" color="text-gray-600" src="user.svg" location="admin.trainers_management" />

                <x-nav-item text="Bookings" color="text-gray-600" src="calendar.svg" location="admin.bookings_management" style="bg-blue-50 border-r-4 border-blue-500" />

                <x-nav-item text="Classes" color="text-gray-600" src="class.svg" location="admin.classes_management" />
                <x-nav-item text="Equipment" color="text-gray-600" src="equipment.svg" location="admin.equipments_management" />
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

        <div class="flex-1 ml-64">
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Booking Management</h2>
                </div>

                <div class="mb-6">
                    <form method="GET" action="{{ route('admin.bookings_management') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari nama member atau trainer..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Search
                        </button>
                        @if(request('search'))
                        <a href="{{ route('admin.bookings_management') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            Clear
                        </a>
                        @endif
                    </form>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3">No</th>
                                    <th class="px-6 py-3">Member</th>
                                    <th class="px-6 py-3">Trainer</th>
                                    <th class="px-6 py-3">Tanggal & Waktu</th>
                                    <th class="px-6 py-3">Durasi</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($bookings as $index => $booking)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $bookings->firstItem() + $index }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($booking->membership->user && $booking->membership->user->image_url)
                                            <img src="{{ $booking->membership->user->image_url }}"
                                                alt="{{ $booking->membership->user->name }}"
                                                class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                            @else
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs border border-indigo-200">
                                                {{ substr($booking->membership->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            @endif

                                            <div>
                                                <p class="font-medium text-gray-900">{{ $booking->membership->user->name ?? 'User Tidak Dikenal' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($booking->trainer && $booking->trainer->image_url)
                                            <img src="{{ $booking->trainer->image_url }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                            @else
                                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs border border-purple-200">
                                                {{ substr($booking->trainer->name ?? 'T', 0, 1) }}
                                            </div>
                                            @endif
                                            <p class="font-medium text-gray-900">{{ $booking->trainer->name ?? 'Trainer Terhapus' }}</p>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</span>
                                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($booking->time)->format('H:i') }} WIB</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-medium border border-gray-200">
                                            <i class="far fa-clock mr-1"></i> {{ $booking->duration }} Menit
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        @php
                                        $bookingDate = \Carbon\Carbon::parse($booking->date . ' ' . $booking->time);
                                        $isPast = $bookingDate->isPast();
                                        @endphp

                                        @if($isPast)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                            Selesai
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                            Mendatang
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">
                                        Belum ada data booking yang tersedia.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>

            </main>
        </div>
    </div>
</body>

</html>