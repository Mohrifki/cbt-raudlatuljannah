@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - CBT Smaradja</title>
    <link rel="icon" type="image/png" href="<?= asset('images/logo.png') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #nav-toggle { display: none; }
        .app-sidebar { transform: translateX(-100%); transition: transform .2s ease; }
        #nav-toggle:checked ~ .app-sidebar { transform: translateX(0); }
        .app-overlay { display: none; background: rgba(0,0,0,.4); }
        #nav-toggle:checked ~ .app-overlay { display: block; }
        .app-content { transition: padding-left .2s ease; }

        @media (min-width: 768px) {
            /* Default desktop: sidebar tampil, konten bergeser */
            .app-sidebar { transform: translateX(0); }
            .app-content { padding-left: 16rem; }
            .app-overlay { display: none !important; }
            /* Saat ☰ ditekan: sidebar disembunyikan, konten melebar penuh */
            #nav-toggle:checked ~ .app-sidebar { transform: translateX(-100%); }
            #nav-toggle:checked ~ .app-content { padding-left: 0; }
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Saklar tersembunyi penggerak sidebar -->
    <input type="checkbox" id="nav-toggle">

    <!-- SIDEBAR -->
    <aside class="app-sidebar fixed inset-y-0 left-0 z-40 w-64 bg-white border-r flex flex-col">
        <div class="h-16 flex items-center gap-2 px-6 border-b shrink-0">
            <img src="<?= asset('images/logo.png') ?>" alt="Logo" class="w-9 h-9 object-contain">
            <span class="font-bold text-lg">CBT <span class="text-green-600">Smaradja</span></span>
        </div>
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="<?= route('admin.dashboard') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= request()->routeIs('admin.dashboard') ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
                <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
            </a>
            <a href="<?= route('admin.users.index') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= request()->routeIs('admin.users.*') ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
                <i class="fa-solid fa-users w-5 text-center"></i> Manajemen User
            </a>
            <a href="<?= route('admin.subjects.index') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= request()->routeIs('admin.subjects.*') ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
                <i class="fa-solid fa-book w-5 text-center"></i> Mata Pelajaran
            </a>
            <a href="<?= route('admin.classes.index') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= request()->routeIs('admin.classes.*') ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
                <i class="fa-solid fa-school w-5 text-center"></i> Kelas
            </a>
        </nav>
        <div class="p-4 border-t shrink-0">
            <form method="POST" action="<?= route('logout') ?>">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- OVERLAY (klik untuk menutup di HP) -->
    <label for="nav-toggle" class="app-overlay fixed inset-0 z-30"></label>

    <!-- KONTEN UTAMA -->
    <div class="app-content">
        <header class="h-16 bg-white border-b flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <label for="nav-toggle" class="text-gray-600 text-xl cursor-pointer hover:text-green-600" title="Tampilkan/Sembunyikan menu">
                    <i class="fa-solid fa-bars"></i>
                </label>
                <h1 class="font-semibold text-gray-800"><?= $title ?></h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-gray-600 text-sm hidden sm:block"><?= e(auth()->user()->name) ?></span>
                <div class="w-9 h-9 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                    <?= strtoupper(substr(auth()->user()->name, 0, 1)) ?>
                </div>
            </div>
        </header>

        <main class="p-4 sm:p-6">
            <?= $slot ?>
        </main>
    </div>

    <!-- JS biasa (tanpa Alpine/npm): simpan pilihan hide sidebar di desktop -->
    <script>
        (function () {
            var cb = document.getElementById('nav-toggle');
            if (!cb) return;
            var isDesktop = function () { return window.matchMedia('(min-width: 768px)').matches; };
            // Pulihkan pilihan terakhir (hanya untuk desktop)
            if (isDesktop()) {
                cb.checked = localStorage.getItem('sidebarHidden') === '1';
            }
            // Simpan pilihan saat ditekan di desktop
            cb.addEventListener('change', function () {
                if (isDesktop()) {
                    localStorage.setItem('sidebarHidden', cb.checked ? '1' : '0');
                }
            });
        })();
    </script>

</body>
</html>