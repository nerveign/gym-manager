<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitAja - Capai Tujuan Kebugaran Anda Bersama Kami</title>

    {{-- Link CDN untuk Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .trainer-card {
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-white text-gray-700 antialiased">

    <header class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200" id="header">
        <nav class="container mx-auto px-6 py-5 flex justify-between items-center">

            {{-- Logo Ikon Font Awesome --}}
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#3662e3] rounded-lg flex items-center justify-center">
                    <i class="fas fa-dumbbell text-white text-lg"></i>
                </div>
                <span class="text-2xl font-bold text-gray-900">FitAja</span>
            </a>

            <div class="hidden md:flex space-x-8">
                <a href="#fitur" class="text-gray-600 hover:text-[#3662e3] transition duration-300 font-medium">Fitur</a>
                <a href="#harga" class="text-gray-600 hover:text-[#3662e3] transition duration-300 font-medium">Harga</a>
                <a href="#trainer" class="text-gray-600 hover:text-[#3662e3] transition duration-300 font-medium">Trainer</a>
                <a href="#testimoni" class="text-gray-600 hover:text-[#3662e3] transition duration-300 font-medium">Testimoni</a>
            </div>

            <div class="hidden md:flex items-center space-x-3">
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#3662e3] transition duration-300 px-4 py-2 font-medium">Masuk</a>
                <a href="{{ route('register') }}" class="bg-[#3662e3] hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-full transition duration-300">
                    Daftar Sekarang
                </a>
            </div>

            <div class="md:hidden">
                <button class="text-gray-600 hover:text-[#3662e3] focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </nav>
    </header>

    <main>
        <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-16 -mt-20">
            {{-- Lapisan 1: Gambar Background --}}
            <div class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('{{ asset('images/background gym.png') }}');">
            </div>

            {{-- Lapisan 2: Overlay Blur dan Konten --}}
            <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>

            {{-- Lapisan 3: Konten Teks --}}
            <div class="relative z-10 container mx-auto px-6 text-center">
                <div class="max-w-4xl mx-auto p-8 rounded-lg">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold mb-6 text-white leading-tight" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                        Capai Tujuan Kebugaran Anda Bersama Kami
                    </h1>
                    <p class="text-lg md:text-xl text-gray-200 mb-10 max-w-2xl mx-auto" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                        Manajemen membership, jadwal kelas, dan tracking progres dalam satu platform.
                    </p>
                    <a href="{{ route('register') }}" class="bg-[#3662e3] hover:bg-blue-700 text-white font-bold py-4 px-10 rounded-full transition duration-300 text-lg transform hover:scale-105 inline-block shadow-lg">
                        Coba Gratis
                    </a>
                </div>
            </div>
        </section>

        <section id="fitur" class="py-24 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-base font-semibold text-[#3662e3] tracking-wider uppercase">Fitur Lengkap</h2>
                    <p class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Dirancang untuk Gym Anda</p>
                    <p class="mt-6 text-lg leading-8 text-gray-600 max-w-2xl mx-auto">Semua yang Anda butuhkan untuk operasional gym yang modern dan efisien.</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="flex flex-col items-start bg-gray-50 p-6 rounded-xl border border-gray-200 hover:border-[#3662e3]/50 shadow-sm transition-colors duration-300">
                        <div class="w-12 h-12 bg-blue-100 text-[#3662e3] rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                <rect width="7" height="7" x="3" y="3" rx="1" />
                                <rect width="7" height="7" x="3" y="14" rx="1" />
                                <path d="M14 4h7" />
                                <path d="M14 9h7" />
                                <path d="M14 15h7" />
                                <path d="M14 20h7" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">Jadwal Kelas Interaktif</h3>
                        <p class="text-gray-600">Booking kelas favorit Anda secara online kapan saja, lihat kapasitas tersisa.</p>
                    </div>
                    <div class="flex flex-col items-start bg-gray-50 p-6 rounded-xl border border-gray-200 hover:border-[#3662e3]/50 shadow-sm transition-colors duration-300">
                        <div class="w-12 h-12 bg-blue-100 text-[#3662e3] rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">Trainer Profesional</h3>
                        <p class="text-gray-600">Dapatkan bimbingan dari para ahli kebugaran bersertifikat kami.</p>
                    </div>
                    <div class="flex flex-col items-start bg-gray-50 p-6 rounded-xl border border-gray-200 hover:border-[#3662e3]/50 shadow-sm transition-colors duration-300">
                        <div class="w-12 h-12 bg-blue-100 text-[#3662e3] rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                <path d="m6 6 7-4 7 4" />
                                <path d="m10 10 4 4" />
                                <path d="m10 14 4-4" />
                                <path d="M18 6 7 4 7 4" />
                                <path d="M5 8v8" />
                                <path d="M19 8v8" />
                                <path d="M2 11h20" />
                                <path d="M3 17h2" />
                                <path d="M19 17h2" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">Tracking Progres Real-time</h3>
                        <p class="text-gray-600">Pantau perkembangan latihan Anda dan capai target lebih cepat.</p>
                    </div>
                    <div class="flex flex-col items-start bg-gray-50 p-6 rounded-xl border border-gray-200 hover:border-[#3662e3]/50 shadow-sm transition-colors duration-300">
                        <div class="w-12 h-12 bg-blue-100 text-[#3662e3] rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                <path d="M14.4 14.4 9.6 9.6" />
                                <path d="M18.657 5.343a2.828 2.828 0 1 1-4 4L6.343 17.657a2.828 2.828 0 1 1-4 4L17.657 6.343a2.828 2.828 0 1 1 4-4Z" />
                                <path d="m21.5 2.5-1 1" />
                                <path d="m3.5 20.5-1 1" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">Fasilitas Modern</h3>
                        <p class="text-gray-600">Berlatih dengan nyaman menggunakan peralatan gym terbaik.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="harga" class="py-24 bg-gray-50">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold mb-4 text-gray-900">Paket Membership Kami</h2>
                    <p class="text-gray-600 max-w-xl mx-auto">Kami membuat semuanya sederhana. Satu paket, semua fasilitas, tanpa biaya lain.</p>
                </div>

                <div class="grid lg:grid-cols-2 gap-12 items-center max-w-4xl mx-auto">

                    <div class="text-left">
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Satu Harga, Akses Penuh.</h3>
                        <p class="text-gray-600 mb-4 text-lg">
                            Kami percaya pada kebugaran yang transparan dan mudah diakses. Paket bulanan kami dirancang untuk memberi Anda semua yang Anda butuhkan untuk sukses tanpa harus memikirkan biaya tambahan.
                        </p>
                        <ul class="mt-6 space-y-3">
                            <li class="flex items-center text-gray-700">
                                <span class="text-green-500 mr-3">✔</span> Tidak ada biaya pendaftaran.
                            </li>
                            <li class="flex items-center text-gray-700">
                                <span class="text-green-500 mr-3">✔</span> Batalkan paket Anda kapan saja.
                            </li>
                            <li class="flex items-center text-gray-700">
                                <span class="text-green-500 mr-3">✔</span> Akses penuh ke semua peralatan dan kelas gym.
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-200 flex flex-col">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-2">Bulanan</h3>
                        <p class="text-gray-600 mb-6 flex-grow">Pilihan terbaik untuk hasil maksimal.</p>
                        <p class="text-5xl font-extrabold text-gray-900 mb-6">Rp 200k</p>
                        <ul class="text-left space-y-3 text-gray-600 mb-8">
                            <li class="flex items-center"><span class="text-green-500 mr-2">✔</span> Akses semua alat</li>
                            <li class="flex items-center"><span class="text-green-500 mr-2">✔</span> Akses semua kelas</li>
                            <li class="flex items-center"><span class="text-green-500 mr-2">✔</span> Konsultasi trainer gratis</li>
                            <li class="flex items-center"><span class="text-green-500 mr-2">✔</span> Wi-Fi & Loker</li>
                        </ul>
                        <a href="{{ route('register') }}" class="mt-auto w-full block text-center bg-[#3662e3] hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-full transition duration-300">
                            Pilih Paket
                        </a>
                    </div>
                </div>
            </div>
        </section>


        <section id="trainer" class="py-24 bg-white">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-4xl font-bold mb-4 text-gray-900">Trainer Profesional Kami</h2>
                <p class="text-gray-600 mb-16 max-w-xl mx-auto">Bertemu dengan para ahli yang akan membantu Anda mencapai setiap target kebugaran.</p>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="group relative rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ asset('images/trainer1.jpg') }}" alt="Trainer 1" class="w-full h-96 object-cover transform group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-6">
                            <h3 class="text-2xl font-bold text-white">Budi Santoso</h3>
                            <p class="text-blue-300">Weightlifting</p>
                        </div>
                    </div>
                    <div class="group relative rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ asset('images/trainer2.jpg') }}" alt="Trainer 2" class="w-full h-96 object-cover transform group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-6">
                            <h3 class="text-2xl font-bold text-white">Sarah Larasati</h3>
                            <p class="text-blue-300">Strength</p>
                        </div>
                    </div>
                    <div class="group relative rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ asset('images/trainer3.jpg') }}" alt="Trainer 3" class="w-full h-96 object-cover transform group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-6">
                            <h3 class="text-2xl font-bold text-white">Rangga Wicaksono</h3>
                            <p class="text-blue-300">Cardio</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimoni" class="py-24 bg-gray-50">
            <div class="container mx-auto px-6">
                <h2 class="text-4xl font-bold mb-16 text-gray-900 text-center">Apa Kata Member Kami</h2>
                <div class="grid lg:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <p class="text-gray-700 mb-6">"Fasilitasnya lengkap dan bersih! Fitur tracking progress di aplikasinya sangat memotivasi saya untuk terus berlatih lebih keras. Highly recommended!"</p>
                        <div class="flex items-center">
                            <img src="{{ asset('images/member1.png') }}" class="w-12 h-12 rounded-full mr-4" alt="Member 1">
                            <div>
                                <p class="font-semibold text-gray-900">Ahmad Fauzi</p>
                                <p class="text-sm text-gray-500">Member Sejak 2025</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <p class="text-gray-700 mb-6">"Jadwal kelasnya beragam dan mudah di-booking. Para trainernya juga sangat profesional dan ramah. Saya suka sekali kelas Gym pagi di sini."</p>
                        <div class="flex items-center">
                            <img src="{{ asset('images/member2.png') }}" class="w-12 h-12 rounded-full mr-4" alt="Member 2">
                            <div>
                                <p class="font-semibold text-gray-900">Dewi Lestari</p>
                                <p class="text-sm text-gray-500">Member Sejak 2025</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="bg-gray-100 border-t border-gray-200 pt-16 pb-8">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="mb-8 lg:mb-0">

                    {{-- UBAH BAGIAN INI: Logo Teks diganti Logo Ikon --}}
                    <a href="#" class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-[#3662e3] rounded-lg flex items-center justify-center">
                            <i class="fas fa-dumbbell text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">FitAja</span>
                    </a>
                    {{-- AKHIR PERUBAHAN LOGO FOOTER --}}

                    <p class="text-gray-600 mb-4">Sekaran, Kec. Gn. Pati, Kota Semarang, Jawa Tengah 50229</p>
                    <p class="text-gray-600"><strong>Telepon:</strong> (024) 123-4567</p>
                    <p class="text-gray-600"><strong>Email:</strong> fitajagym@gmail.com</p>
                </div>

                <div class="mb-8 lg:mb-0">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Jam Operasional</h3>
                    <p class="text-gray-600">Senin - Jumat: 06:00 - 22:00</p>
                    <p class="text-gray-600">Sabtu - Minggu: 07:00 - 20:00</p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-500 hover:text-[#3662e3] transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.494v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.323-1.325z" />
                            </svg></a>
                        <a href="#" class="text-gray-500 hover:text-[#3662e3] transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.584-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.85s.012-3.584.07-4.85c.149-3.227 1.664-4.771 4.919 4.919 1.266-.057 1.645-.069 4.85-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.947s-.014-3.667-.072-4.947c-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.689-.073-4.948-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.79 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44 1.441-.645 1.441-1.44c0-.795-.645-1.44-1.441-1.44z" />
                            </svg></a>
                        <a href="#" class="text-gray-500 hover:text-[#3662e3] transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616v.064c0 2.298 1.634 4.212 3.794 4.654-.713.194-1.465.223-2.204.084.624 1.956 2.444 3.379 4.6 3.419-1.79 1.396-4.063 2.228-6.522 2.228-.423 0-.84-.025-1.248-.073 2.308 1.476 5.065 2.344 8.04 2.344 9.638 0 14.922-7.994 14.593-14.956.993-.715 1.855-1.611 2.542-2.639z" />
                            </svg></a>
                    </div>
                </div>

                <div class="mb-8 lg:mb-0">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Lokasi Kami</h3>
                    <div class="rounded-lg overflow-hidden h-48 bg-gray-200">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d16746.10593526652!2d110.3904599!3d-7.047966349999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b3a1e3a1529%3A0x4cda1f81771c5e97!2sUniversitas%20Negeri%20Semarang%20(UNNES)!5e1!3m2!1sid!2sid!4v1761996788657!5m2!1sid!2sid" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-300 text-center text-gray-500">
                <p>© {{ date('Y') }} FitAja. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

</body>

</html>