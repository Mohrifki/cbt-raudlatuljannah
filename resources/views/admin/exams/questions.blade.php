<x-admin-layout title="Kelola Soal Ujian">
    <?php $rp = request()->routeIs('guru.*') ? 'guru' : 'admin'; ?>
    <div class="max-w-3xl mx-auto">
        <a href="<?= route($rp . '.exams.index') ?>"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i
                class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Ujian</a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5">
                <h2 class="text-white font-bold text-lg"><i class="fa-solid fa-list-check"></i> Kelola Soal</h2>
                <p class="text-green-50 text-sm"><?= e($exam->title) ?> — Mapel: <?= e($exam->subject->name ?? '-') ?>
                </p>
            </div>

            <div class="p-6 space-y-5">
                @if (session('success'))
                    <div class="rounded bg-green-100 text-green-800 px-4 py-2 text-sm"><?= session('success') ?></div>
                @endif

                <!-- FILTER dipindah ke ATAS, di luar cek kosong -->
                <form method="GET" class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Filter Tingkat:</label>
                    <select name="grade" onchange="this.form.submit()"
                        class="border-gray-300 rounded-lg text-sm focus:border-green-500 focus:ring-green-500">
                        <option value="" <?= ($grade ?? '') === '' ? 'selected' : '' ?>>Semua</option>
                        <option value="10" <?= ($grade ?? '') === '10' ? 'selected' : '' ?>>Kelas 10</option>
                        <option value="11" <?= ($grade ?? '') === '11' ? 'selected' : '' ?>>Kelas 11</option>
                        <option value="12" <?= ($grade ?? '') === '12' ? 'selected' : '' ?>>Kelas 12</option>
                    </select>
                    <span class="text-xs text-gray-400">Soal "Semua Tingkat" selalu ikut tampil.</span>
                </form>

                @if ($questions->isEmpty())
                    @if ($totalForSubject === 0)
                        <div class="rounded-lg bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 text-sm">
                            Belum ada soal untuk mapel <b><?= e($exam->subject->name ?? '-') ?></b>. Pastikan soal di
                            Bank Soal memakai mapel yang <b>sama persis</b>, atau tambahkan di <a
                                href="<?= route($rp . '.questions.create') ?>" class="underline font-medium">Bank
                                Soal</a>.
                        </div>
                    @else
                        <div class="rounded-lg bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 text-sm">
                            Tidak ada soal pada filter tingkat <b><?= e($grade) ?></b>. Coba ganti filter ke
                            <b>Semua</b>. (Total soal mapel ini: <b><?= $totalForSubject ?></b>)
                        </div>
                    @endif
                @else
                    <form action="<?= route($rp . '.exams.questions.sync', $exam) ?>" method="POST" class="space-y-4">
                        @csrf

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                                <input type="checkbox" id="check-all"
                                    class="rounded text-green-600 focus:ring-green-500"> Pilih semua
                            </label>
                            <span class="text-sm text-gray-500">Dipilih: <b id="sel-count">0</b> dari
                                <?= $questions->count() ?> soal</span>
                        </div>

                        <div class="border border-gray-200 rounded-lg divide-y max-h-[28rem] overflow-y-auto">
                            @foreach ($questions as $q)
                                <?php
                                if ($q->type === 'pilihan_ganda') {
                                    $badge = 'bg-blue-100 text-blue-800';
                                    $label = 'PG';
                                } elseif ($q->type === 'coding') {
                                    $badge = 'bg-amber-100 text-amber-800';
                                    $label = 'Coding';
                                } else {
                                    $badge = 'bg-purple-100 text-purple-800';
                                    $label = 'Essay';
                                }
                                ?>
                                <label class="flex items-start gap-3 p-3 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="question_ids[]" value="<?= $q->id ?>"
                                        class="q-check mt-1 rounded text-green-600 focus:ring-green-500"
                                        <?= in_array($q->id, old('question_ids', $selected)) ? 'checked' : '' ?>>
                                    <span class="flex-1">
                                        <span
                                            class="text-sm text-gray-700"><?= e(\Illuminate\Support\Str::limit(strip_tags($q->question), 90)) ?></span>
                                        <span class="block mt-1 space-x-2">
                                            <span
                                                class="px-2 py-0.5 rounded text-xs font-medium <?= $badge ?>"><?= $label ?></span>
                                            <span class="text-xs text-gray-400">Skor: <?= $q->score ?></span>
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div class="w-full sm:w-72">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah soal acak <span
                                    class="text-gray-400 font-normal">(opsional)</span></label>
                            <input type="number" name="question_count" min="1"
                                value="<?= old('question_count', $exam->question_count) ?>"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500"
                                placeholder="Kosongkan = pakai semua soal terpilih">
                            <p class="text-xs text-gray-400 mt-1">Mis. pilih 20 soal, isi 10 → tiap siswa dapat 10 soal
                                acak dari 20.</p>
                            @error('question_count')
                                <p class="text-red-600 text-sm mt-1"><?= $message ?></p>
                            @enderror
                        </div>

                        <div class="flex justify-end pt-3 border-t">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 shadow"><i
                                    class="fa-solid fa-floppy-disk"></i> Simpan Soal</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        (function() {
            const boxes = document.querySelectorAll('.q-check');
            const counter = document.getElementById('sel-count');
            const all = document.getElementById('check-all');

            function update() {
                if (counter) counter.textContent = document.querySelectorAll('.q-check:checked').length;
            }
            if (all) all.addEventListener('change', function() {
                boxes.forEach(b => b.checked = all.checked);
                update();
            });
            boxes.forEach(b => b.addEventListener('change', update));
            update();
        })();
    </script>
</x-admin-layout>
