<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">

        <a href="<?= route('dashboard') ?>"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-5">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>

        <div class="flex items-center gap-3 mb-6">
            <div
                class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-600 to-emerald-500 flex items-center justify-center text-white shadow-sm">
                <i class="fa-solid fa-award text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Nilai Saya</h1>
                <p class="text-sm text-gray-500">Rekap hasil ujian yang sudah kamu kerjakan</p>
            </div>
        </div>

        @if ($attempts->isEmpty())
            <div class="bg-white border border-gray-100 rounded-3xl p-12 text-center">
                <i class="fa-regular fa-folder-open text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">Belum ada ujian yang kamu kerjakan.</p>
                <p class="text-sm text-gray-400">Nilai akan muncul di sini setelah kamu mengumpulkan ujian.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($attempts as $a)
                    <?php $isMenunggu = $menunggu[$a->id] ?? false; ?>
                    <div
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between gap-4 flex-wrap">
                        <div class="min-w-0">
                            <h3 class="font-bold text-gray-800 truncate"><?= e(optional($a->exam)->title ?? '-') ?></h3>
                            <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-1 flex-wrap">
                                <i class="fa-solid fa-book-open"></i>
                                <?= e(optional(optional($a->exam)->subject)->name ?? '-') ?>
                                <span class="mx-1 text-gray-300">|</span>
                                <i class="fa-regular fa-clock"></i>
                                <?= e(optional($a->finished_at)?->format('d M Y H:i') ?? '-') ?>
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                @if ($isMenunggu)
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold"><i
                                            class="fa-solid fa-hourglass-half"></i> Menunggu Dinilai</span>
                                @else
                                    <p class="text-2xl font-extrabold text-gray-800 leading-none">
                                        <?= (float) ($a->score ?? 0) ?></p>
                                    <span class="text-xs text-green-600 font-medium">Selesai Dinilai</span>
                                @endif
                            </div>
                            @if ($a->exam)
                                <a href="<?= route('siswa.exams.result', $a->exam) ?>"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-green-600 text-white text-xs font-medium hover:bg-green-700 transition"><i
                                        class="fa-solid fa-eye"></i> Detail</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
