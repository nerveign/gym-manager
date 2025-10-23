<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitPro Manager - Gym Management System</title>
    <meta name="description" content="Platform management gym modern untuk mengelola member, jadwal, dan operasional fitness center">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0f1419] text-gray-100 antialiased">
    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/gym-hero.jpeg') }}')">
            <div class="absolute inset-0 bg-gradient-to-b from-[#0f1419]/95 via-[#0f1419]/85 to-[#0f1419]"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 container mx-auto px-4 text-center">
            <div class="flex items-center justify-center mb-6">
                <svg class="w-16 h-16 text-[#3b9eff]" style="filter: drop-shadow(0 0 40px rgba(59, 158, 255, 0.3))" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            
            <h1 class="text-6xl md:text-7xl lg:text-8xl font-extrabold mb-6 bg-gradient-to-r from-gray-100 to-gray-400 bg-clip-text text-transparent tracking-tight">
                FitAja Manager
            </h1>
            
            <p class="text-xl md:text-2xl text-gray-400 mb-12 max-w-2xl mx-auto">
                Platform management gym berbasis web untuk mengelola member, jadwal, dan operasional fitness center Anda
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-lg font-semibold bg-[#3b9eff] text-white hover:bg-[#3b9eff]/90 hover:shadow-[0_0_30px_rgba(59,158,255,0.5)] transition-all duration-300 h-14 px-8">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-lg font-semibold border border-[#3b9eff]/50 bg-transparent text-white hover:bg-[#3b9eff]/10 hover:border-[#3b9eff] transition-all duration-300 h-14 px-8">
                    Daftar Sekarang
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-[#3b9eff]/50 rounded-full flex items-start justify-center p-2">
                <div class="w-1 h-3 bg-[#3b9eff] rounded-full"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 px-4">
        <div class="container mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold text-center mb-16 tracking-tight">
                Fitur Unggulan
            </h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature Card 1 -->
                <div class="bg-gradient-to-br from-[#1a2129] to-[#1f2630] p-8 rounded-2xl border border-[#2d3748] hover:border-[#3b9eff]/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="text-[#3b9eff] mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Management Member</h3>
                    <p class="text-gray-400">Kelola data member, membership, dan pembayaran dengan mudah</p>
                </div>

                <!-- Feature Card 2 -->
                <div class="bg-gradient-to-br from-[#1a2129] to-[#1f2630] p-8 rounded-2xl border border-[#2d3748] hover:border-[#3b9eff]/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="text-[#3b9eff] mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Jadwal Kelas</h3>
                    <p class="text-gray-400">Atur jadwal kelas fitness dan personal training secara efisien</p>
                </div>

                <!-- Feature Card 3 -->
                <div class="bg-gradient-to-br from-[#1a2129] to-[#1f2630] p-8 rounded-2xl border border-[#2d3748] hover:border-[#3b9eff]/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="text-[#3b9eff] mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Laporan & Analitik</h3>
                    <p class="text-gray-400">Monitor performa gym dengan laporan detail dan real-time</p>
                </div>

                <!-- Feature Card 4 -->
                <div class="bg-gradient-to-br from-[#1a2129] to-[#1f2630] p-8 rounded-2xl border border-[#2d3748] hover:border-[#3b9eff]/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="text-[#3b9eff] mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Equipment Tracking</h3>
                    <p class="text-gray-400">Pantau kondisi dan maintenance peralatan gym</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 px-4">
        <div class="container mx-auto">
            <div class="bg-gradient-to-br from-[#1a2129] to-[#1f2630] rounded-3xl p-12 md:p-16 text-center border border-[#2d3748]">
                <h2 class="text-4xl md:text-5xl font-bold mb-6 tracking-tight">
                    Siap Meningkatkan Bisnis Gym Anda?
                </h2>
                <p class="text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                    Bergabung dengan FitPro Manager dan rasakan kemudahan mengelola gym Anda
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-lg font-semibold bg-[#3b9eff] text-white hover:bg-[#3b9eff]/90 hover:shadow-[0_0_30px_rgba(59,158,255,0.5)] transition-all duration-300 h-14 px-8">
                    Mulai Gratis Sekarang
                </a>
            </div>
        </div>
    </section>
</body>
</html>