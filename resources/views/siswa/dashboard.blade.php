<script>
    document.addEventListener('DOMContentLoaded', function() {
        const g = document.getElementById('greeting');
        const j = document.getElementById('jam');

        function tick() {
            const d = new Date();
            const h = d.getHours();
            if (g) g.textContent = h < 11 ? 'Selamat pagi' : (h < 15 ? 'Selamat siang' : (h < 18 ?
                'Selamat sore' : 'Selamat malam'));
            if (j) j.textContent = d.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        tick();
        setInterval(tick, 1000);
    });
</script>
<x-app-layout>
    <?php $inisial = strtoupper(mb_substr($user->name, 0, 1)); ?>

    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">

        <!-- HERO -->
        <div
            class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-green-600 via-emerald-500 to-teal-500 p-8 text-white shadow-lg">
            <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-white/10"></div>
            <div class="absolute -right-16 bottom-0 w-48 h-48 rounded-full bg-white/5"></div>
            <div class="relative flex items-center gap-5">
                <div
                    class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl font-bold ring-2 ring-white/30">
                    <?= $inisial ?></div>
                <div>
                    <p class="text-green-50 text-sm"><span id="greeting">Halo</span>, 👋 <span
                            class="opacity-60 mx-1">•</span> <span id="jam" class="font-medium"></span></p>
                    <h1 class="text-2xl font-bold"><?= e($user->name) ?></h1>
                    <p class="text-green-50 text-sm mt-0.5">
                        <i class="fa-solid fa-graduation-cap"></i>
                        Kelas: <?= e($user->schoolClass->name ?? 'Belum diatur') ?>
                        <span class="mx-2 opacity-50">•</span>
                        <i class="fa-regular fa-calendar"></i> <?= now()->format('d M Y') ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl"><i
                        class="fa-solid fa-clipboard-list"></i></div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $total ?></p>
                    <p class="text-sm text-gray-500">Total Ujian</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $belum ?></p>
                    <p class="text-sm text-gray-500">Belum Dikerjakan</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $selesai ?></p>
                    <p class="text-sm text-gray-500">Selesai</p>
                </div>
            </div>
        </div>

        <!-- DAFTAR UJIAN TERBARU -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800"><i class="fa-solid fa-bolt text-green-600"></i> Ujian Terbaru</h2>
                <a href="<?= route('siswa.exams.index') ?>"
                    class="text-sm text-green-600 font-medium hover:underline">Lihat
                    semua <i class="fa-solid fa-arrow-right text-xs"></i></a>
            </div>

            <?php if ($exams->isEmpty()): ?>
            <div class="px-6 py-12 text-center text-gray-400">
                <i class="fa-regular fa-face-smile text-3xl mb-2"></i>
                <p>Belum ada ujian tersedia. Santai dulu! ☕</p>
            </div>
            <?php else: ?>
            <div class="divide-y divide-gray-50">
                @foreach ($exams->take(5) as $exam)
                    <?php
                    $attempt = $attempts[$exam->id] ?? null;
                    $now = now();
                    $belumMulai = $exam->start_at && $now->lt($exam->start_at);
                    $sudahTutup = $exam->end_at && $now->gt($exam->end_at);
                    if ($attempt && $attempt->status === 'submitted') {
                        $badge = 'bg-gray-100 text-gray-600';
                        $badgeText = 'Selesai';
                    } elseif ($attempt && $attempt->status === 'ongoing') {
                        $badge = 'bg-blue-100 text-blue-700';
                        $badgeText = 'Sedang dikerjakan';
                    } elseif ($belumMulai) {
                        $badge = 'bg-amber-100 text-amber-700';
                        $badgeText = 'Belum dibuka';
                    } elseif ($sudahTutup) {
                        $badge = 'bg-red-100 text-red-700';
                        $badgeText = 'Ditutup';
                    } else {
                        $badge = 'bg-green-100 text-green-700';
                        $badgeText = 'Tersedia';
                    }
                    ?>
                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i
                                class="fa-solid fa-file-pen"></i></div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 truncate"><?= e($exam->title) ?></p>
                            <p class="text-xs text-gray-500"><?= e($exam->subject->name ?? '-') ?> •
                                <?= $exam->duration ?> menit</p>
                        </div>
                        <span
                            class="px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap <?= $badge ?>"><?= $badgeText ?></span>
                    </div>
                @endforeach
            </div>
            <?php endif; ?>
        </div>

        <!-- TOMBOL AKSI -->
        <a href="<?= route('siswa.exams.index') ?>"
            class="block text-center bg-green-600 text-white font-semibold py-3.5 rounded-2xl hover:bg-green-700 shadow-md transition">
            <i class="fa-solid fa-play"></i> Mulai Kerjakan Ujian
        </a>
    </div>
</x-app-layout>
