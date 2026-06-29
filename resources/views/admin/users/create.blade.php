<div id="siswa-fields" class="rounded-xl border border-dashed border-green-300 bg-green-50/50 p-4">
    <p class="text-sm font-semibold text-green-700 mb-3 flex items-center gap-2"><i class="fa-solid fa-graduation-cap"></i> Data Siswa</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">NIS</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-id-card"></i></span>
                <input type="text" name="nis" value="<?= old('nis') ?>" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Nomor Induk Siswa">
            </div>
            @error('nis')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-school"></i></span>
                <select name="class_id" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">— Pilih Kelas —</option>
                    @foreach ($classes as $class)
                        <option value="<?= $class->id ?>" <?= old('class_id') == $class->id ? 'selected' : '' ?>><?= e($class->name) ?> (<?= e($class->grade) ?>)</option>
                    @endforeach
                </select>
            </div>
            @error('class_id')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
        </div>
    </div>

    <div class="mt-5">
        <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fa-solid fa-list-check text-green-600"></i> Mapel Pilihan (Peminatan)</label>
        @if ($electives->isEmpty())
            <p class="text-sm text-gray-400">Belum ada mapel bertipe "pilihan". Tambahkan dulu di menu Mata Pelajaran.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach ($electives as $elective)
                    <label class="flex items-center gap-2 px-3 py-2 bg-white border rounded-lg cursor-pointer hover:bg-green-50">
                        <input type="checkbox" name="elective_subjects[]" value="<?= $elective->id ?>" <?= in_array($elective->id, old('elective_subjects', [])) ? 'checked' : '' ?> class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="text-sm text-gray-700"><?= e($elective->name) ?></span>
                    </label>
                @endforeach
            </div>
        @endif
    </div>
</div>