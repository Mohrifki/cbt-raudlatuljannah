<x-app-layout>
    <?php
        $inisial = strtoupper(mb_substr($user->name, 0, 1));
        $fotoUrl = $user->photo ? asset('storage/' . $user->photo) : null;
    ?>

    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">

        <!-- HERO -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-green-600 via-emerald-500 to-teal-500 p-8 text-white shadow-lg">
            <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-white/10"></div>
            <div class="absolute -right-16 bottom-0 w-48 h-48 rounded-full bg-white/5"></div>
            <div class="relative flex items-center gap-5">
                @if ($fotoUrl)
                    <img src="<?= $fotoUrl ?>" alt="Foto" class="w-16 h-16 rounded-2xl object-cover object-top ring-2 ring-white/30 shrink-0">
                @else
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl font-bold ring-2 ring-white/30 shrink-0"><?= $inisial ?></div>
                @endif
                <div>
                    <p class="text-green-50 text-sm"><span id="greeting">Halo</span>, 👋 <span class="opacity-60 mx-1">•</span> <span id="jam" class="font-medium"></span></p>
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
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl"><i class="fa-solid fa-clipboard-list"></i></div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $total ?></p>
                    <p class="text-sm text-gray-500">Total Ujian</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl"><i class="fa-solid fa-hourglass-half"></i></div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $belum ?></p>
                    <p class="text-sm text-gray-500">Belum Dikerjakan</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-xl"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $selesai ?></p>
                    <p class="text-sm text-gray-500">Selesai</p>
                </div>
            </div>
        </div>

        <!-- UJIAN TERBARU -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 flex items-center gap-2"><i class="fa-solid fa-bolt text-green-500"></i> Ujian Terbaru</h2>
                <a href="<?= route('siswa.exams.index') ?>" class="text-sm text-green-600 hover:text-green-700 font-medium">Lihat semua →</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($exams->take(5) as $exam)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50">
                        <div>
                            <p class="font-medium text-gray-800"><?= e($exam->title ?? $exam->name ?? 'Ujian') ?></p>
                            <p class="text-sm text-gray-500"><?= e($exam->subject->name ?? '-') ?></p>
                        </div>
                        <a href="<?= route('siswa.exams.index') ?>" class="text-sm text-green-600 hover:text-green-700 font-medium">Buka →</a>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-regular fa-face-smile text-3xl mb-2"></i>
                        <p>Belum ada ujian tersedia. Santai dulu! 🍵</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- TOMBOL -->
        <a href="<?= route('siswa.exams.index') ?>" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3.5 rounded-2xl shadow transition">
            <i class="fa-solid fa-play"></i> Mulai Kerjakan Ujian
        </a>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const g = document.getElementById('greeting');
            const j = document.getElementById('jam');
            function tick() {
                const d = new Date();
                const h = d.getHours();
                if (g) g.textContent = h < 11 ? 'Selamat pagi' : (h < 15 ? 'Selamat siang' : (h < 18 ? 'Selamat sore' : 'Selamat malam'));
                if (j) j.textContent = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            }
            tick();
            setInterval(tick, 1000);
        });
    </script>
</x-app-layout>