<x-admin-layout title="Detail Pengerjaan">
    <?php
    $rp = request()->routeIs('guru.*') ? 'guru' : 'admin';
    $isOngoing = $attempt->status === 'ongoing';
    $durasi = '–';
    if ($attempt->started_at && $attempt->finished_at) {
        $menit = $attempt->started_at->diffInMinutes($attempt->finished_at);
        $durasi = $menit . ' menit';
    }
    ?>

    <a href="<?= route($rp . '.monitoring.show', $attempt->exam) ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i class="fa-solid fa-arrow-left"></i> Kembali ke Peserta Ujian</a>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2"><i class="fa-solid fa-circle-check"></i> <?= e(session('success')) ?></div>
    @endif
    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> Periksa kembali nilai yang dimasukkan (harus angka ≥ 0).</div>
    @endif

    <!-- Info ringkas -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">
        <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-user-graduate"></i></div>
                <div>
                    <h2 class="text-white font-bold text-lg"><?= e(optional($attempt->user)->name ?? '-') ?></h2>
                    <p class="text-green-50 text-sm"><?= e(optional($attempt->exam)->title ?? '-') ?> · <?= e(optional(optional($attempt->exam)->subject)->name ?? '-') ?></p>
                </div>
            </div>
            @if ($isOngoing)
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-white/20 text-white text-xs font-semibold"><i class="fa-solid fa-pen-to-square"></i> Sedang Mengerjakan</span>
            @else
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-white/20 text-white text-xs font-semibold"><i class="fa-solid fa-circle-check"></i> Selesai</span>
            @endif
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-100 border-t border-gray-100">
            <div class="p-4 text-center">
                <p class="text-xs text-gray-500">Nilai</p>
                <p class="text-2xl font-extrabold text-gray-800"><?= (float) ($attempt->score ?? 0) ?></p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-gray-500">Pelanggaran</p>
                <p class="text-2xl font-extrabold <?= (int) $attempt->violation_count > 0 ? 'text-red-500' : 'text-gray-800' ?>"><?= (int) $attempt->violation_count ?></p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-gray-500">Mulai</p>
                <p class="text-sm font-semibold text-gray-700 mt-1"><?= e(optional($attempt->started_at)?->format('d M Y H:i') ?? '-') ?></p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-gray-500">Selesai · Durasi</p>
                <p class="text-sm font-semibold text-gray-700 mt-1"><?= e(optional($attempt->finished_at)?->format('d M Y H:i') ?? '–') ?> · <?= $durasi ?></p>
            </div>
        </div>
    </div>

    <!-- Detail pelanggaran -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
        <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-red-500"></i> Detail Pelanggaran <span class="text-sm font-normal text-gray-400">(<?= $violations->count() ?>)</span></h3>
        @if ($violations->isEmpty())
            <p class="text-sm text-gray-400 italic flex items-center gap-2"><i class="fa-solid fa-shield-halved text-green-500"></i> Tidak ada pelanggaran tercatat.</p>
        @else
            <ol class="relative border-l-2 border-red-100 ml-2 space-y-4">
                @foreach ($violations as $v)
                    <?php
                    $r = strtolower((string) $v->type);
                    if (str_contains($r, 'layar penuh') || str_contains($r, 'fullscreen')) { $ic = 'fa-compress'; }
                    elseif (str_contains($r, 'jendela') || str_contains($r, 'aplikasi')) { $ic = 'fa-window-restore'; }
                    elseif (str_contains($r, 'halaman') || str_contains($r, 'tab')) { $ic = 'fa-arrows-turn-right'; }
                    elseif (str_contains($r, 'salin') || str_contains($r, 'copy') || str_contains($r, 'tempel') || str_contains($r, 'paste') || str_contains($r, 'potong') || str_contains($r, 'cut')) { $ic = 'fa-clipboard'; }
                    else { $ic = 'fa-triangle-exclamation'; }
                    ?>
                    <li class="ml-5">
                        <span class="absolute -left-[11px] flex items-center justify-center w-5 h-5 rounded-full bg-red-100 text-red-600 text-[10px]"><i class="fa-solid <?= $ic ?>"></i></span>
                        <p class="text-sm font-medium text-gray-800"><?= e($v->type) ?></p>
                        <p class="text-xs text-gray-400"><i class="fa-regular fa-clock"></i> <?= e(optional($v->created_at)?->format('d M Y H:i:s') ?? '-') ?></p>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>

    <!-- Rincian jawaban -->
    <?php $adaEsai = $items->first(fn($it) => in_array((optional($it->question)->type ?? ''), ['essay', 'coding'])) !== null; ?>
    <form method="POST" action="<?= route($rp . '.monitoring.grade', $attempt) ?>">
        @csrf
        @method('PUT')
        <div class="space-y-4">
        @foreach ($items as $no => $item)
            <?php
            $q = $item->question;
            $ans = $item->answer;
            $type = $q->type ?? 'pilihan_ganda';
            $jawabanKosong = !$ans || $ans->answer === null || trim((string) $ans->answer) === '';
            ?>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3 gap-3 flex-wrap">
                    <span class="font-bold text-gray-800">Soal <?= $no + 1 ?></span>
                    <div class="flex items-center gap-2">
                        @if ($type === 'coding')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold"><i class="fa-solid fa-code"></i> Coding</span>
                        @elseif ($type === 'essay')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold"><i class="fa-solid fa-pen-nib"></i> Esai</span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold"><i class="fa-solid fa-list-ul"></i> Pilihan Ganda</span>
                        @endif
                        <span class="text-xs text-gray-500">Skor: <b><?= (float) ($ans->score ?? 0) ?></b> / <?= (float) ($q->score ?? 0) ?></span>
                    </div>
                </div>

                <div class="prose prose-sm max-w-none text-gray-800 mb-4"><?= $q->question ?></div>

                @if ($type === 'pilihan_ganda')
                    <?php
                    $opts = ['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d, 'E' => $q->option_e];
                    $chosen = strtoupper(trim((string) ($ans->answer ?? '')));
                    $correct = strtoupper(trim((string) $q->correct_option));
                    ?>
                    <div class="space-y-2">
                        @foreach ($opts as $key => $val)
                            @if ($val !== null && trim((string) $val) !== '')
                                <?php
                                $isCorrect = $key === $correct;
                                $isChosen = $key === $chosen;
                                if ($isCorrect) {
                                    $cls = 'border-green-300 bg-green-50';
                                } elseif ($isChosen) {
                                    $cls = 'border-red-300 bg-red-50';
                                } else {
                                    $cls = 'border-gray-200 bg-white';
                                }
                                ?>
                                <div class="flex items-start gap-3 border rounded-lg px-3 py-2 <?= $cls ?>">
                                    <span class="w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 shrink-0"><?= $key ?></span>
                                    <span class="text-sm text-gray-800 flex-1"><?= e($val) ?></span>
                                    @if ($isCorrect)
                                        <span class="text-xs font-semibold text-green-600 whitespace-nowrap"><i class="fa-solid fa-check"></i> Kunci</span>
                                    @endif
                                    @if ($isChosen)
                                        <span class="text-xs font-semibold <?= $isCorrect ? 'text-green-600' : 'text-red-600' ?> whitespace-nowrap"><i class="fa-solid fa-user"></i> Jawaban Siswa</span>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @if ($jawabanKosong)
                        <p class="mt-3 text-sm text-gray-400 italic">Siswa tidak menjawab soal ini.</p>
                    @endif
                @else
                    <p class="text-sm font-semibold text-gray-600 mb-1">Jawaban Siswa</p>
                    @if ($jawabanKosong)
                        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-400 italic">(kosong)</div>
                    @elseif ($type === 'coding')
                        <pre style="text-align:left !important" class="bg-gray-900 text-gray-100 text-xs rounded-lg p-4 overflow-x-auto"><code><?= e($ans->answer) ?></code></pre>
                    @else
                        <div style="text-align:left !important" class="bg-gray-50 rounded-lg p-4 text-sm text-gray-800 whitespace-pre-wrap"><?= e($ans->answer) ?></div>
                    @endif

                    @if (!empty($q->answer_key))
                        <details class="mt-3">
                            <summary class="cursor-pointer text-sm font-semibold text-green-700"><i class="fa-solid fa-key"></i> Lihat Kunci Jawaban</summary>
                            <div style="text-align:left !important" class="mt-2 bg-green-50 border border-green-100 rounded-lg p-4 text-sm text-gray-800 prose prose-sm max-w-none"><?= $q->answer_key ?></div>
                        </details>
                    @endif

                    @if ($ans)
                        <div class="mt-4 flex items-center gap-3 flex-wrap bg-amber-50 border border-amber-100 rounded-lg p-3">
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2"><i class="fa-solid fa-pen-to-square text-amber-500"></i> Nilai:</label>
                            <input type="number" step="0.01" min="0" max="<?= (float) ($q->score ?? 0) ?>" name="scores[<?= $ans->id ?>]" value="<?= $ans->score !== null ? (float) $ans->score : '' ?>" class="w-28 rounded-lg border border-gray-300 text-sm px-3 py-1.5 focus:ring-green-500 focus:border-green-500" placeholder="0">
                            <span class="text-sm text-gray-500">dari maksimal <b><?= (float) ($q->score ?? 0) ?></b></span>
                        </div>
                    @else
                        <p class="mt-3 text-xs text-gray-400 italic">Siswa tidak menjawab — belum ada yang bisa dinilai.</p>
                    @endif
                @endif
            </div>
        @endforeach

        @if ($items->isEmpty())
            <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-sm">
                <i class="fa-regular fa-file text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">Tidak ada data soal untuk pengerjaan ini.</p>
            </div>
        @endif
        </div>

        @if ($adaEsai)
            <div class="mt-5 flex items-center justify-end gap-3">
                <span class="text-xs text-gray-400">Nilai akhir dihitung ulang otomatis setelah disimpan.</span>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition"><i class="fa-solid fa-floppy-disk"></i> Simpan Nilai Esai/Coding</button>
            </div>
        @endif
    </form>
</x-admin-layout>