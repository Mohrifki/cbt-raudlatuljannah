<x-admin-layout title="Tambah Soal">
    <div class="max-w-3xl mx-auto">
        <a href="<?= route('admin.questions.index') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i class="fa-solid fa-arrow-left"></i> Kembali ke Bank Soal</a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-file-circle-plus"></i></div>
                <div>
                    <h2 class="text-white font-bold text-lg">Tambah Soal</h2>
                    <p class="text-green-50 text-sm">Buat soal baru untuk bank soal</p>
                </div>
            </div>

            <form action="<?= route('admin.questions.store') ?>" method="POST" class="p-6 space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Mata Pelajaran</label>
                        <select name="subject_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">— Pilih Mapel —</option>
                            @foreach ($subjects as $subject)
                                <option value="<?= $subject->id ?>" <?= old('subject_id') == $subject->id ? 'selected' : '' ?>><?= e($subject->name) ?></option>
                            @endforeach
                        </select>
                        @error('subject_id')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Soal</label>
                        <select name="type" id="type-select" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="essay" <?= old('type', 'essay') === 'essay' ? 'selected' : '' ?>>Essay</option>
                            <option value="pilihan_ganda" <?= old('type') === 'pilihan_ganda' ? 'selected' : '' ?>>Pilihan Ganda</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pertanyaan</label>
                    <input type="hidden" name="question" id="question-input" value="<?= e(old('question')) ?>">
                    <div id="question-editor"><?= old('question') ?></div>
                    <p class="text-xs text-gray-400 mt-1">Rumus: klik tombol formula (√x) lalu tulis LaTeX, mis. <code>E = mc^2</code> atau <code>\frac{a}{b}</code>.</p>
                    @error('question')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                </div>

                <!-- Pilihan ganda -->
                <div id="pg-options" class="space-y-3" style="display:none;">
                    <p class="text-sm font-medium text-gray-700">Pilihan Jawaban <span class="text-gray-400 font-normal">(klik bulatan di kiri sebagai kunci jawaban)</span></p>
                    @foreach (['a','b','c','d','e'] as $opt)
                        <div class="flex items-center gap-3">
                            <input type="radio" name="correct_option" value="<?= $opt ?>" <?= old('correct_option') === $opt ? 'checked' : '' ?> class="text-green-600 focus:ring-green-500">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 font-semibold text-gray-600 uppercase shrink-0"><?= $opt ?></span>
                            <input type="text" name="option_<?= $opt ?>" value="<?= old('option_'.$opt) ?>" class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Pilihan <?= strtoupper($opt) ?><?= $opt === 'e' ? ' (opsional)' : '' ?>">
                        </div>
                    @endforeach
                    @error('correct_option')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                    @error('option_a')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                </div>

                <!-- Essay: kunci/rubrik -->
                <div id="essay-answer">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kunci Jawaban / Rubrik <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input type="hidden" name="answer_key" id="answer-input" value="<?= e(old('answer_key')) ?>">
                    <div id="answer-editor"><?= old('answer_key') ?></div>
                </div>

                <div class="w-full sm:w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Skor / Poin</label>
                    <input type="number" name="score" value="<?= old('score', 1) ?>" min="1" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                    @error('score')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="<?= route('admin.questions.index') ?>" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 font-semibold px-5 py-2.5 rounded-lg hover:bg-gray-200">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 shadow"><i class="fa-solid fa-floppy-disk"></i> Simpan Soal</button>
                </div>
            </form>
        </div>
    </div>

    @include('admin.questions.partials.editor-assets')
</x-admin-layout>