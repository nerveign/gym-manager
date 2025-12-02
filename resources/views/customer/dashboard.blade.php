<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard | FitAja</title>
    
    {{-- GOOGLE FONT INTER --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- CUSTOM FONT STYLE --}}
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- ================= SIDEBAR ================= --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                
                <x-nav-item text="Home" color="text-zinc-700" src="home.svg" location="customer.dashboard" style="bg-blue-50 border-r-4 border-blue-500" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
                <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
                <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                 <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ $user->image_url ?? asset('images/default-user.png') }}" alt="{{ $user->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Customer</p>
                    </div>
                </div>
                <div>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 mr-1">
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="flex-1 ml-64">
            <main class="pt-6 pb-8 px-6 h-screen overflow-y-auto scroll-container">
                {{-- Header --}}
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Welcome back, {{ Str::words($user->name, 1, '') }}!</h2>
                    <p class="text-gray-600">Here's your activity overview and gym updates.</p>
                </div>

                {{-- === GRID UTAMA (3 KOLOM) === --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 items-stretch">
                    
                    {{-- KOLOM 1 (KIRI): Membership Status --}}
                    <div class="lg:col-span-1 flex flex-col">
                        {{-- MEMBERSHIP CARD --}}
                        <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100 p-6 rounded-2xl shadow-md border-2 border-blue-200 h-full">
                            {{-- Decorative circles --}}
                            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-200/30 rounded-full -mr-16 -mt-16"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-indigo-200/30 rounded-full -ml-12 -mb-12"></div>
                            
                            {{-- Header --}}
                            <div class="relative z-10 flex justify-between items-start mb-8">
                                <h3 class="text-gray-700 text-sm font-semibold">Membership Status</h3>
                                <div class="w-8 h-8 bg-blue-100 border border-blue-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-arrow-up text-blue-600 text-xs"></i>
                                </div>
                            </div>

                            {{-- Main Content --}}
                            <div class="relative z-10">
                                @if($activeMembership)
                                    {{-- Status Badge --}}
                                    <div class="inline-flex items-center px-3 py-1 bg-white/80 backdrop-blur-sm rounded-full mb-4 shadow-sm border border-blue-200">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                        <span class="text-gray-700 text-xs font-semibold">Active Membership</span>
                                    </div>

                                    {{-- MEMBERSHIP DETAILS --}}
                                    <div class="space-y-3 mb-6">
                                        {{-- Starts --}}
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 text-sm">Starts:</span>
                                            <span class="text-gray-900 font-semibold text-sm">
                                                {{ $activeMembership->start_date ? \Carbon\Carbon::parse($activeMembership->start_date)->format('d M Y') : '20 Oct 2025' }}
                                            </span>
                                        </div>

                                        {{-- Ends --}}
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 text-sm">Ends:</span>
                                            <span class="text-gray-900 font-semibold text-sm">
                                                {{ $activeMembership->end_date ? \Carbon\Carbon::parse($activeMembership->end_date)->format('d M Y') : '19 Nov 2025' }}
                                            </span>
                                        </div>

                                        {{-- Payment Status --}}
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 text-sm">Payment Status:</span>
                                            <span class="inline-flex items-center px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full border border-green-200">
                                                {{ $activeMembership->payment_status ?? 'Paid' }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Days Remaining Indicator --}}
                                    @php
                                        $daysRemaining = $activeMembership->end_date 
                                            ? \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($activeMembership->end_date), false) 
                                            : 30;
                                    @endphp

                                    <div class="flex items-center justify-between bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-sm border border-blue-200">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-blue-100 border border-blue-200 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-clock text-blue-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <p class="text-gray-600 text-xs font-medium">Days Remaining</p>
                                                <p class="text-gray-900 text-xl font-extrabold">{{ max(0, $daysRemaining) }} days</p>
                                            </div>
                                        </div>
                                        
                                        @if($daysRemaining > 0 && $daysRemaining <= 7)
                                            <div class="w-2.5 h-2.5 bg-yellow-500 rounded-full animate-pulse shadow-md"></div>
                                        @elseif($daysRemaining > 7)
                                            <div class="w-2.5 h-2.5 bg-green-500 rounded-full shadow-md"></div>
                                        @else
                                            <div class="w-2.5 h-2.5 bg-red-500 rounded-full shadow-md"></div>
                                        @endif
                                    </div>

                                @else
                                    {{-- No Active Membership --}}
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                            <i class="fas fa-crown text-white text-2xl"></i>
                                        </div>
                                        <h3 class="text-gray-900 text-lg font-bold mb-2">No Active Membership</h3>
                                        <p class="text-gray-600 text-sm mb-4">Get started with a membership plan today!</p>
                                        <a href="#" class="inline-block px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold text-sm rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
                                            View Plans
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM 2 (TENGAH): UPCOMING SCHEDULE --}}
                    <div class="lg:col-span-1 flex flex-col">
                        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 h-full flex flex-col relative overflow-hidden">
                            {{-- Decorative background --}}
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-50 to-pink-50 rounded-full -mr-16 -mt-16 opacity-60"></div>
                            
                            <div class="relative z-10 flex justify-between items-center mb-5 shrink-0">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center border border-purple-200">
                                        <i class="fas fa-calendar-alt text-purple-600 text-sm"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900">Upcoming Schedule</h3>
                                </div>
                                <a href="{{ route('customer.bookings.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">View All →</a>
                            </div>
                            
                            {{-- List Upcoming Schedule --}}
                            <div class="relative z-10 flex-1 overflow-y-auto pr-1">
                                @if($upcomingBookingsData->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($upcomingBookingsData as $booking)
                                            <div class="group bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 p-4 rounded-xl transition-all duration-300 border border-purple-100 hover:border-purple-200 hover:shadow-md">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow border border-purple-100">
                                                            <div class="text-center">
                                                                <p class="text-xs font-semibold text-purple-600">{{ \Carbon\Carbon::parse($booking->date)->format('M') }}</p>
                                                                <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->date)->format('d') }}</p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="font-bold text-gray-900 text-sm group-hover:text-purple-700 transition-colors">
                                                                {{ \Carbon\Carbon::parse($booking->date)->format('D, d M Y') }}
                                                            </p>
                                                            <p class="text-xs text-gray-600 mt-1 flex items-center">
                                                                <i class="far fa-clock mr-1.5 text-purple-400"></i>
                                                                {{ \Carbon\Carbon::parse($booking->time)->format('H:i') }} 
                                                                <span class="mx-1.5">•</span>
                                                                <i class="fas fa-user-tie mr-1.5 text-purple-400"></i>
                                                                {{ Str::limit($booking->trainer->name ?? 'N/A', 15) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-12 h-full flex flex-col justify-center items-center">
                                        <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center mb-4 border-2 border-purple-100">
                                            <i class="fas fa-calendar-times text-purple-400 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium mb-3">No upcoming bookings.</p>
                                        <a href="{{ route('customer.bookings.create') }}" class="inline-flex items-center px-4 py-2.5 bg-purple-50 text-purple-700 font-medium text-sm rounded-lg hover:bg-purple-100 transition-all border border-purple-200 shadow-sm">
                                            <i class="fas fa-plus mr-2 text-xs"></i>
                                            Book a Session
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM 3 (KANAN): Statistik --}}
                    <div class="lg:col-span-1 flex flex-col gap-6">
                        {{-- Enrolled Classes --}}
                        <div class="group relative bg-gradient-to-br from-indigo-50 to-purple-100 border-2 border-indigo-200 p-5 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                            {{-- Decorative Circle --}}
                            <div class="absolute -right-8 -top-8 w-24 h-24 bg-indigo-200/30 rounded-full group-hover:scale-110 transition-transform"></div>
                            
                            <div class="relative z-10 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-14 h-14 bg-indigo-100 border border-indigo-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-all">
                                        <i class="fas fa-users text-indigo-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-gray-600">Enrolled Classes</p>
                                        <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $enrolledClasses }}</p>
                                    </div>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-gray-600 text-lg opacity-0 group-hover:opacity-100 transition-all"></i>
                            </div>
                        </div>

                        {{-- Upcoming Bookings Count --}}
                        <div class="group relative bg-gradient-to-br from-teal-50 to-cyan-100 border-2 border-teal-200 p-5 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                            {{-- Decorative Circle --}}
                            <div class="absolute -right-8 -top-8 w-24 h-24 bg-teal-200/30 rounded-full group-hover:scale-110 transition-transform"></div>
                            
                            <div class="relative z-10 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-14 h-14 bg-teal-100 border border-teal-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-all">
                                        <i class="fas fa-calendar-check text-teal-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-gray-600">Upcoming Bookings</p>
                                        <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $upcomingBookingsCount }}</p>
                                    </div>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-gray-600 text-lg opacity-0 group-hover:opacity-100 transition-all"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- === ROW 2: RECENT PROGRESS === --}}
                <div class="w-full bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 border border-blue-200 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-line text-blue-600 text-sm"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Recent Progress</h3>
                        </div>
                        <a href="{{ route('customer.progress.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium text-sm rounded-lg hover:bg-gray-200 transition-all border border-gray-200">
                            View All
                            <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                    
                    @if($recentProgress->count() > 0)
                        {{-- Grid 2 Kolom --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach($recentProgress as $index => $item)
                                @php
                                    // Gradient colors untuk setiap card
                                    $gradients = [
                                        'from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 border-blue-200',
                                        'from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 border-purple-200',
                                        'from-emerald-50 to-teal-50 hover:from-emerald-100 hover:to-teal-100 border-emerald-200',
                                        'from-orange-50 to-amber-50 hover:from-orange-100 hover:to-amber-100 border-orange-200',
                                    ];
                                    $gradient = $gradients[$index % 4];
                                    
                                    // Icon colors (SOFT)
                                    $iconBgColors = [
                                        'bg-blue-100 border border-blue-200',
                                        'bg-purple-100 border border-purple-200',
                                        'bg-emerald-100 border border-emerald-200',
                                        'bg-orange-100 border border-orange-200',
                                    ];
                                    $iconTextColors = [
                                        'text-blue-600',
                                        'text-purple-600',
                                        'text-emerald-600',
                                        'text-orange-600',
                                    ];
                                    $iconBg = $iconBgColors[$index % 4];
                                    $iconText = $iconTextColors[$index % 4];
                                @endphp
                                
                                <div class="group relative bg-gradient-to-br {{ $gradient }} border-2 p-5 rounded-2xl transition-all duration-300 hover:shadow-xl overflow-hidden">
                                    {{-- Decorative Circle --}}
                                    <div class="absolute -right-6 -top-6 w-20 h-20 bg-white/30 rounded-full group-hover:scale-110 transition-transform duration-300"></div>
                                    
                                    {{-- Content --}}
                                    <div class="relative z-10">
                                        {{-- Header dengan Icon --}}
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-start space-x-3 flex-1">
                                                {{-- Icon Exercise --}}
                                                <div class="w-12 h-12 {{ $iconBg }} rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all shrink-0">
                                                    <i class="fas fa-dumbbell {{ $iconText }} text-lg"></i>
                                                </div>
                                                
                                                {{-- Exercise Name --}}
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-bold text-gray-900 text-lg leading-tight mb-1 truncate">
                                                        {{ $item->exercise }}
                                                    </h4>
                                                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                                                        <span class="inline-flex items-center px-2.5 py-1 bg-white/60 rounded-full font-medium">
                                                            <i class="far fa-clock mr-1.5"></i> 
                                                            {{ $item->duration }} mins
                                                        </span>
                                                        <span class="inline-flex items-center px-2.5 py-1 bg-white/60 rounded-full font-medium">
                                                            <i class="far fa-calendar mr-1.5"></i> 
                                                            {{ $item->created_at ? $item->created_at->format('d M Y') : 'N/A' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Action Buttons --}}
                                            <div class="flex items-center space-x-1 ml-2 shrink-0">
                                                <a href="{{ route('customer.progress.edit', $item->id) }}" 
                                                   class="w-9 h-9 flex items-center justify-center bg-white hover:bg-blue-50 text-blue-600 rounded-lg transition-all shadow-sm hover:shadow-md group/edit">
                                                    <i class="fas fa-pencil-alt text-sm group-hover/edit:scale-110 transition-transform"></i>
                                                </a>
                                                <button type="button" 
                                                        onclick="confirmDelete('{{ route('customer.progress.destroy', $item->id) }}')"
                                                        class="w-9 h-9 flex items-center justify-center bg-white hover:bg-red-50 text-red-600 rounded-lg transition-all shadow-sm hover:shadow-md group/delete">
                                                    <i class="fas fa-trash-alt text-sm group-hover/delete:scale-110 transition-transform"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        {{-- Description --}}
                                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-3 mt-3">
                                            <p class="text-sm text-gray-700 leading-relaxed line-clamp-2">
                                                {{ $item->description ?? 'No description available for this exercise session.' }}
                                            </p>
                                        </div>
                                        
                                        {{-- Progress Badge --}}
                                        <div class="mt-3 flex items-center justify-between">
                                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Session #{{ $loop->iteration }}
                                            </span>
                                            <div class="flex items-center space-x-1">
                                                @for($i = 0; $i < min(5, ceil($item->duration / 20)); $i++)
                                                    <div class="w-1.5 h-1.5 bg-gray-400 rounded-full"></div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl border-2 border-dashed border-gray-300">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                                <i class="fas fa-chart-line text-blue-500 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No Progress Recorded Yet</h3>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                Start tracking your fitness journey today and watch your progress grow!
                            </p>
                            <a href="{{ route('customer.progress.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-100 text-blue-700 font-semibold text-sm rounded-xl hover:bg-blue-200 transition-all border border-blue-200">
                                <i class="fas fa-plus mr-2"></i>
                                Add Your First Progress
                            </a>
                        </div>
                    @endif
                </div>

            </main>
        </div>
    </div>

    {{-- Form Delete Global --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Script SweetAlert2 --}}
    <script>
        function confirmDelete(url) {
            Swal.fire({
                html: `
                    <div class="flex flex-col items-center pt-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Recent Progress?</h2>
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
                    let form = document.getElementById('deleteForm');
                    form.action = url;
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
