<x-admin-layout title="Tambah Kelas">
    <div class="max-w-3xl mx-auto">
        <a href="<?= route('admin.classes.index') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i class="fa-solid fa-arrow-left"></i> Kembali ke daftar kelas</a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-school"></i></div>
                <div>
                    <h2 class="text-white font-bold text-lg">Tambah Kelas</h2>
                    <p class="text-green-50 text-sm">Buat rombongan belajar baru</p>
                </div>
            </div>

            <form action="<?= route('admin.classes.store') ?>" method="POST" class="p-6 space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kelas</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-door-open"></i></span>
                            <input type="text" name="name" value="<?= old('name') ?>" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Contoh: XII IPA 1">
                        </div>
                        @error('name')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tingkat</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-layer-group"></i></span>
                            <select name="grade" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="X" <?= old('grade') === 'X' ? 'selected' : '' ?>>X</option>
                                <option value="XI" <?= old('grade') === 'XI' ? 'selected' : '' ?>>XI</option>
                                <option value="XII" <?= old('grade') === 'XII' ? 'selected' : '' ?>>XII</option>
                            </select>
                        </div>
                        @error('grade')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="<?= route('admin.classes.index') ?>" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 font-semibold px-5 py-2.5 rounded-lg hover:bg-gray-200">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 shadow"><i class="fa-solid fa-floppy-disk"></i> Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>