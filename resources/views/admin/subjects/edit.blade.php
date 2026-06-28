<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Mata Pelajaran</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="<?= route('admin.subjects.update', $subject) ?>" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Mapel</label>
                        <input type="text" name="name" value="<?= e(old('name', $subject->name)) ?>"
                               class="mt-1 w-full rounded-md border-gray-300" required>
                        @error('name') <p class="text-red-600 text-sm mt-1"><?= $message ?></p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kode Mapel</label>
                        <input type="text" name="code" value="<?= e(old('code', $subject->code)) ?>"
                               class="mt-1 w-full rounded-md border-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tipe</label>
                        <?php $type = old('type', $subject->type); ?>
                        <select name="type" class="mt-1 w-full rounded-md border-gray-300" required>
                            <option value="wajib" <?= $type === 'wajib' ? 'selected' : '' ?>>Wajib</option>
                            <option value="pilihan" <?= $type === 'pilihan' ? 'selected' : '' ?>>Pilihan</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Update</button>
                        <a href="<?= route('admin.subjects.index') ?>" class="bg-gray-200 px-4 py-2 rounded-md">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>