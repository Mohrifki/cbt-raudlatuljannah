<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Koreksi Jawaban</h2>
    </x-slot>
    <?php $rp = request()->routeIs('guru.*') ? 'guru' : 'admin'; ?>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Info siswa -->
            <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <p class="text-xs text-gray-500">Siswa</p>
                    <p class="font-bold text-gray-800 text-lg"><?= e(optional($attempt->user)->name ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Ujian</p>
                    <p class="font-semibold text-gray-800"><?= e(optional($attempt->exam)->title ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Waktu Submit</p>
                    <p class="font-semibold text-gray-800">
                        <?= e(optional($attempt->finished_at)?->format('d M Y H:i') ?? '-') ?></p>
                </div>
                <a href="<?= route($rp . '.grading.index') ?>" class="text-sm text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form method="POST" action="<?= route($rp . '.grading.update', $attempt) ?>" class="space-y-6">
                @csrf
                @method('PUT')

                @foreach ($answers as $no => $answer)
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-bold text-gray-800">Soal <?= $no + 1 ?></span>
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold <?= $answer->question->type === 'coding' ? 'bg-indigo-50 text-indigo-700' : 'bg-sky-50 text-sky-700' ?>">
                                <i
                                    class="fa-solid <?= $answer->question->type === 'coding' ? 'fa-code' : 'fa-pen-nib' ?>"></i>
                                <?= $answer->question->type === 'coding' ? 'Coding' : 'Esai' ?>
                            </span>
                        </div>

                        <div class="prose prose-sm max-w-none text-gray-800 mb-4"><?= $answer->question->question ?>
                        </div>

                        <p class="text-sm font-semibold text-gray-600 mb-1">Jawaban Siswa</p>
                        @if ($answer->question->type === 'coding')
                            <pre style="text-align:left !important" class="bg-gray-900 text-gray-100 text-xs rounded-lg p-4 overflow-x-auto"><code><?= e($answer->answer ?? '(kosong)') ?></code></pre>
                        @else
                            <div style="text-align:left !important"
                                class="bg-gray-50 rounded-lg p-4 text-sm text-gray-800 whitespace-pre-wrap">
                                <?= e($answer->answer ?? '(kosong)') ?></div>
                        @endif

                        @if (!empty($answer->question->answer_key))
                            <details class="mt-3">
                                <summary class="cursor-pointer text-sm font-semibold text-green-700">
                                    <i class="fa-solid fa-key"></i> Lihat Kunci Jawaban
                                </summary>
                                <div style="text-align:left !important"
                                    class="mt-2 bg-green-50 border border-green-100 rounded-lg p-4 text-sm text-gray-800 prose prose-sm max-w-none">
                                    <?= $answer->question->answer_key ?></div>
                            </details>
                        @endif

                        <div class="mt-4 flex items-center gap-3">
                            <label class="text-sm font-semibold text-gray-700">Skor</label>
                            <input type="number" step="0.01" min="0"
                                max="<?= (float) (optional($answer->question)->score ?? 100) ?>"
                                name="scores[<?= $answer->id ?>]"
                                value="<?= $answer->score !== null ? (float) $answer->score : '' ?>"
                                class="w-28 rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 text-sm">
                            <span class="text-sm text-gray-500">/
                                <?= (float) (optional($answer->question)->score ?? 0) ?> poin</span>
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end gap-3">
                    <a href="<?= route($rp . '.grading.index') ?>"
                        class="px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100">Batal</a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Nilai
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-admin-layout>
