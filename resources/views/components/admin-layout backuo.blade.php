@php
    $menuTitles = [
        'dashboard' => 'Dashboard',
        'admin.users.*' => 'Manajemen User',
        'admin.subjects.*' => 'Mata Pelajaran',
        'admin.plot-sessions.*' => 'Jadwal Plot',
        'admin.classes.*' => 'Kelas',
        'admin.questions.*' => 'Bank Soal',
        'admin.exams.*' => 'Manajemen Ujian',
        'admin.grading.*' => 'Penilaian',
        'admin.attendance.*' => 'Daftar Hadir',
        'admin.reports.*' => 'Laporan Nilai',
        'admin.settings.*' => 'Pengaturan',
    ];
    $pageTitle = 'Dashboard';
    foreach ($menuTitles as $pattern => $label) {
        if (request()->routeIs($pattern)) {
            $pageTitle = $label;
            break;
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> — CBT Smaradja</title>
    <link rel="icon" href="<?= asset('images/logo.png') ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem;
            background: #fff;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            z-index: 40;
            transform: translateX(-100%);
            transition: width .2s ease, transform .2s ease;
        }

        #nav-toggle:checked~.app-overlay {
            display: block;
        }

        #nav-toggle:checked~.app-sidebar {
            transform: translateX(0);
        }

        .app-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .4);
            z-index: 30;
            display: none;
        }

        .app-main {
            transition: margin .2s ease;
            min-height: 100vh;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem .85rem;
            border-radius: .6rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .nav-link i {
            width: 1.25rem;
            text-align: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        @media (min-width: 768px) {
            .app-sidebar {
                transform: translateX(0);
            }

            .app-overlay {
                display: none !important;
            }

            .app-main {
                margin-left: 16rem;
            }

            body.sidebar-collapsed .app-sidebar {
                width: 5rem;
            }

            body.sidebar-collapsed .app-main {
                margin-left: 5rem;
            }

            body.sidebar-collapsed .nav-label,
            body.sidebar-collapsed .brand-text,
            body.sidebar-collapsed .user-text {
                display: none;
            }

            body.sidebar-collapsed .nav-link {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }

            body.sidebar-collapsed .brand-row {
                justify-content: center;
            }

            body.sidebar-collapsed .user-row {
                justify-content: center;
            }

            /* Tooltip saat collapsed */
            body.sidebar-collapsed .nav-link {
                position: relative;
            }

            body.sidebar-collapsed .nav-link:hover::after {
                content: attr(data-label);
                position: absolute;
                left: 4.6rem;
                top: 50%;
                transform: translateY(-50%);
                background: #111827;
                color: #fff;
                font-size: .75rem;
                padding: .25rem .5rem;
                border-radius: .35rem;
                white-space: nowrap;
                z-index: 60;
            }
        }

        /* ================= DARK MODE ================= */
        .dark body {
            background-color: #0f172a !important;
            color: #e2e8f0;
        }

        /* Cangkang */
        .dark .app-sidebar {
            background: #1e293b !important;
            border-right-color: #334155 !important;
        }

        .dark .app-main {
            background: #0f172a !important;
        }

        .dark .brand-text {
            color: #f1f5f9 !important;
        }

        .dark .nav-link {
            color: #cbd5e1;
        }

        .dark .nav-link:hover {
            background: #334155;
        }


        /* Menu sidebar yang sedang aktif */
        .dark .nav-link.bg-green-50 {
            background-color: #14532d !important;
            /* hijau gelap */
            color: #bbf7d0 !important;
            /* teks hijau terang */
        }

        .dark .app-overlay {
            background: rgba(0, 0, 0, .6);
        }

        /* Bar atas (header / topbar) */
        .dark header,
        .dark .app-topbar,
        .dark .topbar {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }

        /* Latar */
        .dark .bg-white {
            background-color: #1e293b !important;
        }

        .dark .bg-gray-50,
        .dark .bg-gray-100,
        .dark .bg-gray-200 {
            background-color: #0f172a !important;
        }

        /* Teks */
        .dark .text-gray-900,
        .dark .text-gray-800,
        .dark .text-gray-700 {
            color: #e2e8f0 !important;
        }

        .dark .text-gray-600,
        .dark .text-gray-500,
        .dark .text-gray-400 {
            color: #94a3b8 !important;
        }

        /* Garis / border / divider */
        .dark .border,
        .dark .border-b,
        .dark .border-t,
        .dark .border-l,
        .dark .border-r,
        .dark .border-gray-100,
        .dark .border-gray-200,
        .dark .border-gray-300 {
            border-color: #334155 !important;
        }

        .dark [class*="divide-"]>*+* {
            border-color: #334155 !important;
        }

        /* Form */
        .dark input,
        .dark select,
        .dark textarea {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }

        .dark ::placeholder {
            color: #64748b !important;
        }

        /* Hover baris tabel / item (perbaiki teks tak terlihat saat disorot) */
        .dark .hover\:bg-gray-50:hover,
        .dark .hover\:bg-gray-100:hover,
        .dark .hover\:bg-gray-200:hover,
        .dark .hover\:bg-white:hover {
            background-color: #334155 !important;
        }

        /* Ikon toggle tema */
        .theme-icon-dark {
            display: none;
        }

        .dark .theme-icon-dark {
            display: inline;
        }

        .dark .theme-icon-light {
            display: none;
        }
    </style>
    <script>
        // Terapkan state collapsed sebelum render (hindari kedip)
        if (localStorage.getItem('sidebarCollapsed') === '1') document.documentElement.classList.add('pre-collapsed');
    </script>
</head>

<body class="bg-gray-100">
    <?php
    $active = fn($pattern) => request()->routeIs($pattern) ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-100';
    $u = auth()->user();
    $initial = strtoupper(substr($u->name ?? 'U', 0, 1));
    ?>

    <input type="checkbox" id="nav-toggle" class="hidden">
    <label for="nav-toggle" class="app-overlay md:hidden"></label>

    <!-- SIDEBAR -->
    <aside class="app-sidebar">
        <!-- Brand + tombol collapse -->
        <div class="brand-row flex items-center justify-between gap-2 px-4 h-16 border-b border-gray-100">
            <a href="<?= route('admin.dashboard') ?>" class="flex items-center gap-2 overflow-hidden">
                <img src="<?= asset('images/logo.png') ?>" alt="Logo" class="w-9 h-9 object-contain shrink-0">
                <span class="brand-text font-bold text-gray-800 leading-tight">CBT <span
                        class="text-green-600">Smaradja</span></span>
            </a>
            <button type="button" onclick="toggleSidebar()"
                class="hidden md:flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 shrink-0"
                title="Sembunyikan / tampilkan menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <button type="button" onclick="toggleTheme()" title="Ganti tema gelap/terang"
                class="flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100">
                <i class="fa-solid fa-moon theme-icon-light"></i>
                <i class="fa-solid fa-sun theme-icon-dark"></i>
            </button>
        </div>

        <!-- Menu -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <a href="<?= route('admin.dashboard') ?>" data-label="Dashboard"
                class="nav-link <?= $active('admin.dashboard') ?>"><i class="fa-solid fa-gauge-high"></i><span
                    class="nav-label">Dashboard</span></a>
            <a href="<?= route('admin.users.index') ?>" data-label="Manajemen User"
                class="nav-link <?= $active('admin.users.*') ?>"><i class="fa-solid fa-users"></i><span
                    class="nav-label">Manajemen User</span></a>
            <a href="<?= route('admin.subjects.index') ?>" data-label="Mata Pelajaran"
                class="nav-link <?= $active('admin.subjects.*') ?>"><i class="fa-solid fa-book"></i><span
                    class="nav-label">Mata Pelajaran</span></a>
            <a href="<?= route('admin.plot-sessions.index') ?>" data-label="Jadwal Plot"
                class="nav-link <?= $active('admin.plot-sessions.*') ?>">
                <i class="fa-solid fa-layer-group"></i><span class="nav-label">Jadwal Plot</span>
            </a>
            <a href="<?= route('admin.classes.index') ?>" data-label="Kelas"
                class="nav-link <?= $active('admin.classes.*') ?>"><i class="fa-solid fa-chalkboard-user"></i><span
                    class="nav-label">Kelas</span></a>
            <a href="<?= route('admin.questions.index') ?>" data-label="Bank Soal"
                class="nav-link <?= $active('admin.questions.*') ?>"><i
                    class="fa-solid fa-file-circle-question"></i><span class="nav-label">Bank Soal</span></a>
            <a href="<?= route('admin.exams.index') ?>" data-label="Manajemen Ujian"
                class="nav-link <?= $active('admin.exams.*') ?>"><i class="fa-solid fa-clipboard-list"></i><span
                    class="nav-label">Manajemen Ujian</span></a>
            <a href="<?= route('admin.grading.index') ?>" data-label="Penilaian"
                class="nav-link <?= $active('admin.grading.*') ?>">
                <i class="fa-solid fa-clipboard-check"></i><span class="nav-label">Penilaian</span>
            </a>
            <a href="<?= route('admin.attendance.index') ?>" data-label="Daftar Hadir"
                class="nav-link <?= $active('admin.attendance.*') ?>">
                <i class="fa-solid fa-clipboard-user"></i><span class="nav-label">Daftar Hadir</span>
            </a>
            <a href="<?= route('admin.reports.index') ?>" data-label="Laporan Nilai"
                class="nav-link <?= $active('admin.reports.*') ?>">
                <i class="fa-solid fa-chart-column"></i><span class="nav-label">Laporan Nilai</span>
            </a>
            <a href="<?= route('admin.settings.edit') ?>" data-label="Pengaturan"
                class="nav-link <?= $active('admin.settings.*') ?>">
                <i class="fa-solid fa-gear"></i><span class="nav-label">Pengaturan</span>
            </a>
        </nav>

        <!-- User bawah -->
        <div class="user-row flex items-center gap-3 px-4 py-3 border-t border-gray-100">
            <div
                class="w-9 h-9 rounded-full bg-green-600 text-white flex items-center justify-center font-semibold shrink-0">
                <?= e($initial) ?></div>
            <div class="user-text flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-700 truncate"><?= e($u->name ?? 'User') ?></p>
                <form method="POST" action="<?= route('logout') ?>">
                    @csrf
                    <button type="submit" class="text-xs text-red-500 hover:underline"><i
                            class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- KONTEN -->
    <div class="app-main">
        <header
            class="sticky top-0 z-20 bg-white/80 backdrop-blur border-b border-gray-100 h-16 flex items-center gap-3 px-4 sm:px-6">
            <label for="nav-toggle"
                class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg text-gray-600 hover:bg-gray-100 cursor-pointer"><i
                    class="fa-solid fa-bars"></i></label>
            <h1 class="text-lg font-bold text-gray-800"><?= e($title) ?></h1>
        </header>
        <main class="p-4 sm:p-6">
            <?= $slot ?>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
        }
        // Terapkan saat load
        if (localStorage.getItem('sidebarCollapsed') === '1') document.body.classList.add('sidebar-collapsed');
    </script>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        }
    </script>

</body>

</html>
