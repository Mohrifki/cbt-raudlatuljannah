<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-clipboard-list text-green-600"></i> Daftar Ujian</h1>
            <p class="text-gray-500 text-sm">Ujian yang tersedia untukmu.</p>
        </div>

        @if ($exams->isEmpty())
            <div class="rounded-xl bg-amber-50 border border-amber-200 text-amber-800 px-4 py-6 text-center">
                Belum ada ujian yang tersedia saat ini. 📭
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($exams as $exam)
                    <?php
                        $attempt = $attempts[$exam->id] ?? null;
                        $now = now();
                        $belumMulai = $exam->start_at && $now->lt($exam->start_at);
                        $sudahTutup = $exam->end_at && $now->gt($exam->end_at);
                        if ($attempt && $attempt->status === 'submitted') { $badge = 'bg-gray-100 text-gray-600'; $badgeText = 'Selesai'; }
                        elseif ($attempt && $attempt->status === 'ongoing') { $badge = 'bg-blue-100 text-blue-700'; $badgeText = 'Sedang dikerjakan'; }
                        elseif ($belumMulai) { $badge = 'bg-amber-100 text-amber-700'; $badgeText = 'Belum dibuka'; }
                        elseif ($sudahTutup) { $badge = 'bg-red-100 text-red-700'; $badgeText = 'Ditutup'; }
                        else { $badge = 'bg-green-100 text-green-700'; $badgeText = 'Tersedia'; }
                    ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h2 class="font-semibold text-gray-800"><?= e($exam->title) ?></h2>
                            <span class="px-2 py-0.5 rounded text-xs font-medium <?= $badge ?>"><?= $badgeText ?></span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1"><i class="fa-solid fa-book"></i> <?= e($exam->subject->name ?? '-') ?></p>
                        <p class="text-sm text-gray-500 mb-1"><i class="fa-solid fa-clock"></i> <?= $exam->duration ?> menit</p>
                        <?php if ($exam->start_at): ?>
                            <p class="text-xs text-gray-400 mb-3"><i class="fa-solid fa-calendar"></i> <?= $exam->start_at->format('d M Y H:i') ?><?= $exam->end_at ? ' - ' . $exam->end_at->format('H:i') : '' ?></p>
                        <?php endif; ?>

                        <div class="mt-auto pt-3">
                            @if ($attempt && $attempt->status === 'submitted')
                                <button disabled class="w-full bg-gray-100 text-gray-400 font-medium py-2 rounded-lg cursor-not-allowed">Sudah Dikerjakan</button>
                            @elseif ($belumMulai || $sudahTutup)
                                <button disabled class="w-full bg-gray-100 text-gray-400 font-medium py-2 rounded-lg cursor-not-allowed"><?= $belumMulai ? 'Belum Dibuka' : 'Ditutup' ?></button>
                            @else
                                <button disabled class="w-full bg-green-600 text-white font-semibold py-2 rounded-lg opacity-60 cursor-not-allowed"><?= $attempt ? 'Lanjutkan' : 'Mulai Ujian' ?> (aktif di 5C-2)</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>