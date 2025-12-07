<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kelas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
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

        <div class="flex-1 ml-64 bg-gray-100 h-screen overflow-hidden">
            <main class="pt-8 pb-8 px-8 h-full overflow-y-auto scroll-container">

                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Kelas</h1>
                        <p class="text-gray-500">Kelola jadwal kelas, trainer, dan materi latihan.</p>
                    </div>
                    <a href="{{ route('admin.classes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm flex items-center">
                        <i class="fas fa-plus mr-2"></i> Tambah Kelas
                    </a>
                </div>

                {{-- Alert Success --}}
                @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
                @endif

                <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <form action="{{ route('admin.classes_management') }}" method="GET" class="flex gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Cari berdasarkan nama kelas atau trainer...">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm">
                            Cari
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trainer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Edit</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($classes as $class)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        {{-- NAMA KELAS JADI LINK KE DETAIL --}}
                                        <a href="{{ route('admin.class.detail', $class->id) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                                            {{ $class->type }}
                                        </a>
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1 max-w-xs">{{ Str::limit($class->description, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-3 flex-shrink-0">
                                                {{ substr($class->trainer->name ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $class->trainer->name ?? 'No Trainer' }}</div>
                                                <div class="text-xs text-gray-500">{{ $class->trainer->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-gray-900 font-medium">
                                                {{ \Carbon\Carbon::parse($class->schedule)->format('d M Y') }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($class->schedule)->format('H:i') }} WIB
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            {{ $class->classMembers->count() }} / {{ $class->capacity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        {{-- HANYA TOMBOL EDIT (Hapus dipindah ke dalam Edit) --}}
                                        <a href="{{ route('admin.classes.edit', $class->id) }}" class="text-gray-400 hover:text-indigo-600 transition">
                                            <i class="fas fa-pencil-alt text-lg"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-dumbbell text-gray-400 text-xl"></i>
                                            </div>
                                            <p class="text-sm font-medium text-gray-900">Tidak ada data kelas ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $classes->withQueryString()->links() }}
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>