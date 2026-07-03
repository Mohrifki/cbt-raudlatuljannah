<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-8">

        <a href="<?= route('dashboard') ?>"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-5">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>

        <div class="flex items-center gap-3 mb-6">
            <div
                class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-600 to-emerald-500 flex items-center justify-center text-white shadow-sm">
                <i class="fa-solid fa-clipboard-list text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Ujian</h1>
                <p class="text-sm text-gray-500">Ujian yang tersedia untukmu 📚</p>
            </div>
        </div>

        <?php
        $semua = $exams ?? collect();
        $wajib = $semua->filter(fn($e) => ($e->type ?? '') === 'wajib');
        $pilihan = $semua->filter(fn($e) => ($e->type ?? '') !== 'wajib');
        $groups = [['label' => 'Ujian Wajib', 'icon' => 'fa-book', 'items' => $wajib, 'bar' => 'from-green-500 to-emerald-500', 'iconBg' => 'bg-green-100 text-green-600', 'chip' => 'text-green-600'], ['label' => 'Ujian Pilihan', 'icon' => 'fa-star', 'items' => $pilihan, 'bar' => 'from-blue-500 to-indigo-500', 'iconBg' => 'bg-blue-100 text-blue-600', 'chip' => 'text-blue-600']];
        ?>

        @if ($semua->isEmpty())
            <div class="bg-white border border-gray-100 rounded-3xl p-12 text-center">
                <i class="fa-regular fa-folder-open text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">Belum ada ujian yang tersedia.</p>
                <p class="text-sm text-gray-400">Ujian akan muncul di sini saat dijadwalkan gurumu.</p>
            </div>
        @else
            @foreach ($groups as $g)
                @if ($g['items']->isNotEmpty())
                    <section class="bg-gray-50 border border-gray-100 rounded-3xl p-5 sm:p-6 mb-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-lg <?= $g['iconBg'] ?> flex items-center justify-center">
                                <i class="fa-solid <?= $g['icon'] ?> text-sm"></i>
                            </div>
                            <h2 class="font-bold text-gray-700"><?= $g['label'] ?></h2>
                            <span
                                class="text-xs font-semibold bg-white border border-gray-200 rounded-full px-2 py-0.5 <?= $g['chip'] ?>"><?= $g['items']->count() ?></span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($g['items'] as $exam)
                                <?php
                                $now = now();
                                $mulai = $exam->start_at;
                                $tutup = $exam->end_at;
                                if ($mulai && $now->lt($mulai)) {
                                    $stLabel = 'Akan Datang';
                                    $stCls = 'bg-amber-100 text-amber-700';
                                    $buka = false;
                                } elseif ($tutup && $now->gt($tutup)) {
                                    $stLabel = 'Ditutup';
                                    $stCls = 'bg-red-100 text-red-600';
                                    $buka = false;
                                } else {
                                    $stLabel = 'Tersedia';
                                    $stCls = 'bg-green-100 text-green-700';
                                    $buka = true;
                                }
                                ?>
                                <div
                                    class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition overflow-hidden flex flex-col">
                                    <div class="h-1.5 bg-gradient-to-r <?= $g['bar'] ?>"></div>
                                    <div class="p-5 flex flex-col flex-1">
                                        <div class="flex items-start justify-between mb-3">
                                            <div
                                                class="w-11 h-11 rounded-xl <?= $g['iconBg'] ?> flex items-center justify-center">
                                                <i class="fa-solid <?= $g['icon'] ?>"></i>
                                            </div>
                                            <span
                                                class="text-xs font-semibold px-2.5 py-1 rounded-full <?= $stCls ?>"><?= $stLabel ?></span>
                                        </div>
                                        <h3 class="font-bold text-gray-800 mb-1 line-clamp-2"><?= e($exam->title) ?>
                                        </h3>
                                        <p class="text-xs text-gray-500 mb-4 flex items-center gap-1.5"><i
                                                class="fa-solid fa-book-open"></i> <?= e($exam->subject->name ?? '-') ?>
                                        </p>
                                        <div class="space-y-1.5 text-xs text-gray-500 mb-4">
                                            <p class="flex items-center gap-2"><i
                                                    class="fa-regular fa-clock w-4 text-center"></i>
                                                <?= (int) $exam->duration ?> menit</p>
                                            <p class="flex items-center gap-2"><i
                                                    class="fa-solid fa-list-ol w-4 text-center"></i>
                                                <?= $exam->questions()->count() ?> soal</p>
                                            <p class="flex items-center gap-2"><i
                                                    class="fa-regular fa-calendar w-4 text-center"></i>
                                                <?= ($mulai ? $mulai->format('d M Y H:i') : '-') . ($tutup ? ' – ' . $tutup->format('H:i') : '') ?>
                                            </p>
                                        </div>
                                        <div class="mt-auto">
                                            @if ($buka)
                                                <a href="<?= route('siswa.exams.start', $exam) ?>"
                                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl flex items-center justify-center gap-2 transition"><i
                                                        class="fa-solid fa-play"></i> Mulai Ujian</a>
                                            @else
                                                <button disabled
                                                    class="w-full bg-gray-100 text-gray-400 font-semibold py-2.5 rounded-xl flex items-center justify-center gap-2 cursor-not-allowed"><i
                                                        class="fa-solid fa-lock"></i> <?= $stLabel ?></button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
        @endif
    </div>
</x-app-layout>
