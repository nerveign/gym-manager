<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - FitAja</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <a href="{{ route('welcome') }}" class="flex items-center justify-center mb-8 gap-3 group">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dumbbell text-white text-lg"></i>
                </div>
                <span class="text-2xl font-bold text-gray-800">FitAja</span>
            </a>

            <!-- Card -->
            <div class="bg-white rounded-xl border shadow-sm">
                <!-- Header -->
                <div class="p-6 space-y-1 border-b border-gray-100">
                    <h1 class="text-2xl font-semibold text-center text-gray-900">
                        Daftar
                    </h1>
                    <p class="text-center text-gray-600">
                        Buat akun baru untuk memulai
                    </p>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Name -->
                        <div class="space-y-2">
                            <label for="name" class="text-sm font-medium text-gray-700 block">
                                Nama
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input 
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Nama lengkap"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    class="w-full pl-10 h-10 px-4 py-2 rounded-lg border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 transition-colors"
                                >
                            </div>
                            @error('name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="text-sm font-medium text-gray-700 block">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input 
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    required
                                    autocomplete="username"
                                    class="w-full pl-10 h-10 px-4 py-2 rounded-lg border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 transition-colors"
                                >
                            </div>
                            @error('email')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="text-sm font-medium text-gray-700 block">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input 
                                    id="password"
                                    type="password"
                                    name="password"
                                    placeholder="Minimal 8 karakter"
                                    required
                                    autocomplete="new-password"
                                    class="w-full pl-10 h-10 px-4 py-2 rounded-lg border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 transition-colors"
                                >
                            </div>
                            @error('password')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="text-sm font-medium text-gray-700 block">
                                Konfirmasi Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input 
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="Ulangi password"
                                    required
                                    autocomplete="new-password"
                                    class="w-full pl-10 h-10 px-4 py-2 rounded-lg border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 transition-colors"
                                >
                            </div>
                            @error('password_confirmation')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-all duration-300 h-10 px-4 py-2"
                        >
                            <i class="fas fa-user-plus"></i>
                            Daftar
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <div class="p-6 pt-0 space-y-4">
                    <div class="text-sm text-center text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                            Masuk di sini
                        </a>
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="mt-8 text-center">
                <a href="{{ route('welcome') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>