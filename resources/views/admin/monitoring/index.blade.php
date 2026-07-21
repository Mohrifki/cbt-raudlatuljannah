<x-admin-layout title="Monitoring Ujian">
    <?php $rp = request()->routeIs('guru.*') ? 'guru' : 'admin'; ?>
    <div class="mb-5 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h3 class="text-lg font-bold text-gray-700">Monitoring & Aktivitas Ujian</h3>
            <p class="text-sm text-gray-500">Pantau peserta yang sedang mengerjakan dan tinjau hasil yang sudah dikumpulkan.</p>
        </div>
        <span class="inline-flex items-center gap-2 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 rounded-full px-3 py-1.5">
            <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
            LIVE · refresh tiap 15 detik
        </span>
    </div>

    @if ($exams->isEmpty())
        <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-sm">
            <i class="fa-solid fa-satellite-dish text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 font-medium">Belum ada aktivitas ujian.</p>
            <p class="text-sm text-gray-400">Kartu akan muncul di sini saat ada siswa yang mulai atau sudah mengerjakan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($exams as $exam)
                <?php $on = (int) ($ongoingCounts[$exam->id] ?? 0); $sub = (int) ($submittedCounts[$exam->id] ?? 0); ?>
                <a href="<?= route($rp . '.monitoring.show', $exam) ?>"
                    class="block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r <?= $on > 0 ? 'from-green-500 to-emerald-500' : 'from-gray-300 to-gray-400' ?>"></div>
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-11 h-11 rounded-xl <?= $on > 0 ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' ?> flex items-center justify-center"><i class="fa-solid fa-desktop"></i></div>
                            @if ($on > 0)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold"><i class="fa-solid fa-user-clock"></i> <?= $on ?> aktif</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-800 mb-1 line-clamp-2"><?= e($exam->title) ?></h3>
                        <p class="text-xs text-gray-500 flex items-center gap-1.5"><i class="fa-solid fa-book-open"></i> <?= e($exam->subject->name ?? '-') ?></p>
                        <div class="mt-4 flex items-center gap-4 text-xs text-gray-500">
                            <span><i class="fa-solid fa-user-clock text-green-500"></i> <?= $on ?> mengerjakan</span>
                            <span><i class="fa-solid fa-circle-check text-gray-400"></i> <?= $sub ?> selesai</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    <script>
        setTimeout(function () { window.location.reload(); }, 15000);
    </script>
</x-admin-layout>