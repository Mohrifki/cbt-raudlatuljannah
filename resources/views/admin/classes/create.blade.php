<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tambah Kelas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="<?= route('admin.classes.store') ?>" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                        <input type="text" name="name" value="<?= e(old('name')) ?>"
                               class="mt-1 w-full rounded-md border-gray-300" placeholder="contoh: X IPA 1" required>
                        @error('name') <p class="text-red-600 text-sm mt-1"><?= $message ?></p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tingkat</label>
                        <select name="grade" class="mt-1 w-full rounded-md border-gray-300">
                            <option value="">- Pilih Tingkat -</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Simpan</button>
                        <a href="<?= route('admin.classes.index') ?>" class="bg-gray-200 px-4 py-2 rounded-md">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>