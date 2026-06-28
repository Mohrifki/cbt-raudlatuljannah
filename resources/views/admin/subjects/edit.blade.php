<x-admin-layout title="Edit Mapel">
    <div class="max-w-3xl mx-auto">
        <a href="<?= route('admin.subjects.index') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i class="fa-solid fa-arrow-left"></i> Kembali ke daftar mapel</a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-book"></i></div>
                <div>
                    <h2 class="text-white font-bold text-lg">Edit Mata Pelajaran</h2>
                    <p class="text-green-50 text-sm"><?= e($subject->name) ?></p>
                </div>
            </div>

            <form action="<?= route('admin.subjects.update', $subject) ?>" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Mapel</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-book-open"></i></span>
                            <input type="text" name="name" value="<?= old('name', $subject->name) ?>" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                        @error('name')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Mapel</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-hashtag"></i></span>
                            <input type="text" name="code" value="<?= old('code', $subject->code) ?>" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                        @error('code')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Mapel</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-layer-group"></i></span>
                        <select name="type" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="wajib" <?= old('type', $subject->type) === 'wajib' ? 'selected' : '' ?>>Wajib</option>
                            <option value="pilihan" <?= old('type', $subject->type) === 'pilihan' ? 'selected' : '' ?>>Pilihan</option>
                        </select>
                    </div>
                    @error('type')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="<?= route('admin.subjects.index') ?>" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 font-semibold px-5 py-2.5 rounded-lg hover:bg-gray-200">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 shadow"><i class="fa-solid fa-floppy-disk"></i> Update Mapel</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>