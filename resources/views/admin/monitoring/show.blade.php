<x-admin-layout title="Monitoring Ujian">
    <?php $rp = request()->routeIs('guru.*') ? 'guru' : 'admin'; ?>
    <a href="<?= route($rp . '.monitoring.index') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Monitoring</a>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-desktop"></i></div>
                <div>
                    <h2 class="text-white font-bold text-lg"><?= e($exam->title) ?></h2>
                    <p class="text-green-50 text-sm"><?= e($exam->subject->name ?? '-') ?> · <?= (int) $exam->duration ?> menit</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-2 text-xs font-semibold text-white bg-white/20 rounded-full px-3 py-1.5">
                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span></span>
                LIVE · 15 detik
            </span>
        </div>

        <div class="p-4 sm:p-6 overflow-x-auto">
            @if ($attempts->isEmpty())
                <div class="text-center text-gray-500 py-12">
                    <i class="fa-solid fa-users-slash text-3xl text-gray-300 mb-3"></i>
                    <p class="font-medium">Belum ada peserta pada ujian ini.</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wide text-left">
                            <th class="py-3 px-4 font-semibold">No</th>
                            <th class="py-3 px-4 font-semibold">Siswa</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                            <th class="py-3 px-4 font-semibold">Progres</th>
                            <th class="py-3 px-4 font-semibold text-center">Pelanggaran</th>
                            <th class="py-3 px-4 font-semibold">Mulai</th>
                            <th class="py-3 px-4 font-semibold text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attempts as $i => $a)
                            <?php
                            $total = is_array($a->question_order) ? count($a->question_order) : 0;
                            $done = (int) ($answered[$a->id] ?? 0);
                            $pct = $total > 0 ? min(100, round($done / $total * 100)) : 0;
                            $isOngoing = $a->status === 'ongoing';
                            $vio = (int) $a->violation_count;
                            ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 text-gray-500"><?= $i + 1 ?></td>
                                <td class="py-3 px-4 font-medium text-gray-800"><?= e(optional($a->user)->name ?? '-') ?></td>
                                <td class="py-3 px-4">
                                    @if ($isOngoing)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold"><i class="fa-solid fa-pen-to-square"></i> Mengerjakan</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2 min-w-[140px]">
                                        <div class="flex-1 h-2 rounded-full bg-gray-100 overflow-hidden">
                                            <div class="h-full bg-green-500" style="width: <?= $pct ?>%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500 whitespace-nowrap"><?= $done ?>/<?= $total ?></span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($vio > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-600 text-xs font-bold"><i class="fa-solid fa-triangle-exclamation"></i> <?= $vio ?></span>
                                    @else
                                        <span class="text-gray-300">–</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-gray-600 whitespace-nowrap"><?= e(optional($a->started_at)?->format('d M H:i') ?? '-') ?></td>
                                <td class="py-3 px-4 text-center">
                                    <a href="<?= route($rp . '.monitoring.attempt', $a) ?>"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-medium hover:bg-indigo-100 transition"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script>
        setTimeout(function () { window.location.reload(); }, 15000);
    </script>
</x-admin-layout>