<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT Ujian Sekolah</title>
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-800 antialiased">
    <nav x-data="{ open: false }" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <img src="<?= asset('images/logo.png') ?>" alt="Logo Sekolah" class="w-10 h-10 object-contain">
                    <span class="font-bold text-xl">CBT<span class="text-green-600">  Smaradja</span></span>
                </div>

                <div class="hidden md:flex items-center gap-6">
                    <a href="#fitur" class="text-gray-600 hover:text-green-600">Fitur</a>
                    <a href="#cara" class="text-gray-600 hover:text-green-600">Cara Kerja</a>
                    <a href="#tentang" class="text-gray-600 hover:text-green-600">Tentang</a>
                    @auth
                        <a href="<?= route('dashboard') ?>"
                            class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">Dashboard</a>
                    @else
                        <a href="<?= route('login') ?>" class="text-gray-700 hover:text-green-600 font-medium">Masuk</a>
                        @if (Route::has('register'))
                            <a href="<?= route('register') ?>"
                                class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 shadow">Daftar</a>
                        @endif
                    @endauth
                </div>
                <button @click="open = !open" class="md:hidden text-gray-700">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="open" x-cloak class="md:hidden bg-white border-t px-4 py-4 space-y-3">
            <a href="#fitur" class="block text-gray-600">Fitur</a>
            <a href="#cara" class="block text-gray-600">Cara Kerja</a>
            <a href="#tentang" class="block text-gray-600">Tentang</a>
            @auth
                <a href="<?= route('dashboard') ?>"
                    class="block bg-green-600 text-white px-4 py-2 rounded-lg text-center">Dashboard</a>
            @else
                <a href="<?= route('login') ?>" class="block text-gray-700">Masuk</a>
                @if (Route::has('register'))
                    <a href="<?= route('register') ?>"
                        class="block bg-green-600 text-white px-4 py-2 rounded-lg text-center">Daftar</a>
                @endif
            @endauth
        </div>
    </nav>
    <section class="bg-gradient-to-b from-green-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span
                    class="inline-block bg-green-100 text-green-700 text-sm font-medium px-3 py-1 rounded-full mb-4">Sistem
                    Ujian Berbasis Komputer</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight">
                    Ujian Online <span class="text-green-600">Aman & Anti-Curang</span>
                </h1>
                <p class="mt-6 text-lg text-gray-600 max-w-lg">
                    Platform CBT untuk sekolah dengan deteksi kecurangan, mode layar terkunci, serta dukungan mata
                    pelajaran wajib maupun pilihan dalam satu sistem.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    @auth
                        <a href="<?= route('dashboard') ?>"
                            class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 shadow-lg">Buka
                            Dashboard</a>
                    @else
                        <a href="<?= route('login') ?>"
                            class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 shadow-lg">Mulai
                            Ujian</a>
                        <a href="#fitur"
                            class="border border-gray-300 px-8 py-3 rounded-lg font-semibold text-gray-700 hover:bg-gray-50">Pelajari
                            Fitur</a>
                    @endauth
                </div>
            </div>


            <div class="relative">
                <div class="bg-white rounded-2xl shadow-2xl p-6 border border-gray-100">
                    <div class="flex gap-1.5 mb-4">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span class="w-3 h-3 rounded-full bg-green-400"></span>
                    </div>
                    <p class="font-semibold text-gray-800 mb-3">Soal No. 1</p>
                    <div class="h-3 bg-gray-100 rounded w-full mb-2"></div>
                    <div class="h-3 bg-gray-100 rounded w-5/6 mb-4"></div>
                    <div class="space-y-2">
                        <div class="border-2 border-green-500 bg-green-50 rounded-lg p-2 text-sm">A. Pilihan jawaban
                            benar</div>
                        <div class="border rounded-lg p-2 text-sm text-gray-600">B. Pilihan jawaban</div>
                        <div class="border rounded-lg p-2 text-sm text-gray-600">C. Pilihan jawaban</div>
                        <div class="border rounded-lg p-2 text-sm text-gray-600">D. Pilihan jawaban</div>
                    </div>
                </div>
                <div
                    class="absolute -top-4 -right-4 bg-green-600 text-white text-sm font-semibold px-4 py-1.5 rounded-full shadow-lg">
                    ⏱ 00:45:00</div>
                <div
                    class="absolute -bottom-4 -left-4 bg-white text-gray-700 text-sm font-medium px-4 py-1.5 rounded-full shadow-lg border">
                    🔒 Mode Aman Aktif</div>
            </div>
        </div>
    </section>
    <section class="bg-white py-12 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <p class="text-3xl font-extrabold text-green-600">100%</p>
                <p class="text-gray-500 mt-1">Berbasis Web</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-green-600">3</p>
                <p class="text-gray-500 mt-1">Level Pengguna</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-green-600">Real-time</p>
                <p class="text-gray-500 mt-1">Monitoring</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-green-600">Auto</p>
                <p class="text-gray-500 mt-1">Penilaian</p>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900">Fitur Unggulan</h2>
                <p class="mt-3 text-gray-600">Dirancang khusus untuk ujian sekolah yang aman, adil, dan mudah diawasi.
                </p>
            </div>
            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="text-3xl mb-3">🔒</div>
                    <h3 class="font-bold text-lg">Mode Layar Terkunci</h3>
                    <p class="text-gray-600 mt-2">Siswa tidak dapat membuka tab lain atau aplikasi pihak ketiga selama
                        ujian berlangsung.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="text-3xl mb-3">🕵️</div>
                    <h3 class="font-bold text-lg">Deteksi Kecurangan</h3>
                    <p class="text-gray-600 mt-2">Sistem mencatat aktivitas mencurigakan seperti pindah tab atau keluar
                        dari layar ujian.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="text-3xl mb-3">📚</div>
                    <h3 class="font-bold text-lg">Mapel Wajib & Pilihan</h3>
                    <p class="text-gray-600 mt-2">Mendukung ujian serentak untuk mapel wajib maupun mapel pilihan yang
                        berbeda tiap siswa.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="text-3xl mb-3">📊</div>
                    <h3 class="font-bold text-lg">Monitoring Real-time</h3>
                    <p class="text-gray-600 mt-2">Guru dapat memantau peserta ujian dan status pengerjaan secara
                        langsung.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="text-3xl mb-3">⏱️</div>
                    <h3 class="font-bold text-lg">Timer Otomatis</h3>
                    <p class="text-gray-600 mt-2">Waktu ujian berjalan otomatis dan jawaban tersimpan saat waktu habis.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="text-3xl mb-3">📈</div>
                    <h3 class="font-bold text-lg">Penilaian Otomatis</h3>
                    <p class="text-gray-600 mt-2">Nilai pilihan ganda dihitung otomatis sehingga hasil langsung
                        tersedia.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="cara" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900">Cara Kerja</h2>
                <p class="mt-3 text-gray-600">Tiga langkah sederhana untuk menjalankan ujian.</p>
            </div>
            <div class="mt-12 grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div
                        class="w-14 h-14 mx-auto rounded-full bg-green-600 text-white flex items-center justify-center text-xl font-bold">
                        1</div>
                    <h3 class="font-bold text-lg mt-4">Admin Menyiapkan</h3>
                    <p class="text-gray-600 mt-2">Admin membuat akun, kelas, mapel, dan jadwal ujian.</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-14 h-14 mx-auto rounded-full bg-green-600 text-white flex items-center justify-center text-xl font-bold">
                        2</div>
                    <h3 class="font-bold text-lg mt-4">Guru Membuat Soal</h3>
                    <p class="text-gray-600 mt-2">Guru menyusun bank soal dan mengatur ujian sesuai mapel.</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-14 h-14 mx-auto rounded-full bg-green-600 text-white flex items-center justify-center text-xl font-bold">
                        3</div>
                    <h3 class="font-bold text-lg mt-4">Siswa Mengerjakan</h3>
                    <p class="text-gray-600 mt-2">Siswa login dan mengerjakan ujian dengan mode aman.</p>
                </div>
            </div>
        </div>
    </section>
    <section id="tentang" class="py-20 bg-green-600">
        <div class="max-w-4xl mx-auto px-4 text-center text-white">
            <h2 class="text-3xl font-bold">Siap Memulai Ujian Digital di Sekolah Anda?</h2>
            <p class="mt-4 text-green-100">Kelola seluruh proses ujian dalam satu sistem yang aman dan mudah.</p>
            <div class="mt-8">
                @auth
                    <a href="<?= route('dashboard') ?>"
                        class="inline-block bg-white text-green-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">Buka
                        Dashboard</a>
                @else
                    <a href="<?= route('login') ?>"
                        class="inline-block bg-white text-green-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">Masuk
                        Sekarang</a>
                @endauth
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-400 py-8">
        <p class="flex items-center gap-2">
            <img src="<?= asset('images/logo.png') ?>" alt="Logo" class="w-6 h-6 object-contain">
            <span class="font-bold text-white">CBTSekolah</span> — Sistem Ujian Berbasis Komputer
        </p>
    </footer>

</body>

</html>
