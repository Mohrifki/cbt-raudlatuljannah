<x-app-layout>
    <div class="max-w-lg mx-auto py-10 px-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-center">
            <div class="bg-gradient-to-br from-green-600 to-emerald-500 p-8 text-white">
                <i class="fa-solid fa-circle-check text-5xl mb-3"></i>
                <h1 class="text-2xl font-bold">Ujian Selesai!</h1>
                <p class="text-green-50 text-sm mt-1"><?= e($exam->title) ?></p>
            </div>
            <div class="p-8">
                <p class="text-sm text-gray-500 mb-1">Skor Sementara</p>
                <p class="text-5xl font-extrabold text-gray-800 mb-6"><?= (float) ($attempt->score ?? 0) ?></p>
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="bg-gray-50 rounded-xl p-3"><p class="text-xl font-bold text-gray-800"><?= $totalSoal ?></p><p class="text-xs text-gray-500">Total Soal</p></div>
                    <div class="bg-green-50 rounded-xl p-3"><p class="text-xl font-bold text-green-600"><?= $benar ?></p><p class="text-xs text-gray-500">Benar (PG)</p></div>
                    <div class="bg-red-50 rounded-xl p-3"><p class="text-xl font-bold text-red-500"><?= (int) $attempt->violation_count ?></p><p class="text-xs text-gray-500">Pelanggaran</p></div>
                </div>
                @if ($adaEsai)
                    <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-xl px-4 py-3 text-sm mb-6"><i class="fa-solid fa-hourglass-half"></i> Soal esai/coding menunggu penilaian guru. Skor akhir dapat berubah.</div>
                @endif
                <div class="flex gap-3">
                    <a href="<?= route('siswa.exams.index') ?>" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl">Daftar Ujian</a>
                    <a href="<?= route('dashboard') ?>" class="flex-1 border border-gray-200 text-gray-700 hover:bg-gray-50 font-semibold py-2.5 rounded-xl">Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>