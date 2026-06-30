<?php
    $exam = $exam ?? null;
    $type = old('type', $exam->type ?? 'wajib');
    $sel = $selectedClasses ?? [];
    $fmt = fn($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('Y-m-d\TH:i') : '';
?>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Ujian</label>
    <input type="text" name="title" value="<?= e(old('title', $exam->title ?? '')) ?>" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="mis. UTS Matematika Kelas X">
    @error('title')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Mata Pelajaran</label>
        <select name="subject_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
            <option value="">— Pilih Mapel —</option>
            @foreach ($subjects as $subject)
                <option value="<?= $subject->id ?>" <?= old('subject_id', $exam->subject_id ?? '') == $subject->id ? 'selected' : '' ?>><?= e($subject->name) ?> (<?= ucfirst($subject->type) ?>)</option>
            @endforeach
        </select>
        @error('subject_id')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Ujian</label>
        <select name="type" id="exam-type" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
            <option value="wajib" <?= $type === 'wajib' ? 'selected' : '' ?>>Wajib (semua siswa di kelas target)</option>
            <option value="pilihan" <?= $type === 'pilihan' ? 'selected' : '' ?>>Pilihan / Peminatan</option>
        </select>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi (menit)</label>
        <input type="number" name="duration" min="1" value="<?= old('duration', $exam->duration ?? 60) ?>" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
        @error('duration')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Mulai</label>
        <input type="datetime-local" name="start_at" value="<?= old('start_at', $fmt($exam->start_at ?? null)) ?>" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
        @error('start_at')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Selesai</label>
        <input type="datetime-local" name="end_at" value="<?= old('end_at', $fmt($exam->end_at ?? null)) ?>" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
        @error('end_at')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
    </div>
</div>

<div class="flex flex-wrap gap-6">
    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
        <input type="checkbox" name="shuffle_questions" value="1" <?= old('shuffle_questions', $exam->shuffle_questions ?? false) ? 'checked' : '' ?> class="rounded text-green-600 focus:ring-green-500"> Acak urutan soal
    </label>
    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
        <input type="checkbox" name="shuffle_options" value="1" <?= old('shuffle_options', $exam->shuffle_options ?? false) ? 'checked' : '' ?> class="rounded text-green-600 focus:ring-green-500"> Acak urutan pilihan (PG)
    </label>
</div>

<!-- Target kelas (wajib) -->
<div id="wajib-target" style="display: <?= $type === 'wajib' ? 'block' : 'none' ?>;">
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas Target</label>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
        @foreach ($classes as $class)
            <label class="inline-flex items-center gap-2 text-sm border border-gray-200 rounded-lg px-3 py-2">
                <input type="checkbox" name="target_classes[]" value="<?= $class->id ?>" <?= in_array($class->id, old('target_classes', $sel)) ? 'checked' : '' ?> class="rounded text-green-600 focus:ring-green-500">
                <?= e($class->name) ?>
            </label>
        @endforeach
    </div>
    @error('target_classes')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
</div>

<!-- Info pilihan -->
<div id="pilihan-note" style="display: <?= $type === 'pilihan' ? 'block' : 'none' ?>;" class="rounded-lg bg-purple-50 border border-purple-100 text-purple-800 text-sm px-4 py-3">
    <i class="fa-solid fa-circle-info"></i> Ujian pilihan otomatis hanya diberikan ke siswa yang memilih mapel ini sebagai peminatan. Tidak perlu pilih kelas.
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
    <select name="status" class="w-full sm:w-60 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
        <option value="draft" <?= old('status', $exam->status ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft (belum tampil ke siswa)</option>
        <option value="published" <?= old('status', $exam->status ?? '') === 'published' ? 'selected' : '' ?>>Published (aktif sesuai jadwal)</option>
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
    <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Instruksi/keterangan ujian"><?= e(old('description', $exam->description ?? '')) ?></textarea>
</div>

<script>
    (function () {
        const sel = document.getElementById('exam-type');
        const wajib = document.getElementById('wajib-target');
        const pilihan = document.getElementById('pilihan-note');
        function toggle() {
            const t = sel.value;
            wajib.style.display = (t === 'wajib') ? 'block' : 'none';
            pilihan.style.display = (t === 'pilihan') ? 'block' : 'none';
        }
        sel.addEventListener('change', toggle);
    })();
</script>