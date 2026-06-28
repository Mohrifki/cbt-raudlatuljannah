<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Manajemen Mata Pelajaran</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2">
                        <?= session('success') ?>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-700">Daftar Mata Pelajaran</h3>
                    <a href="<?= route('admin.subjects.create') ?>"
                       class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        + Tambah Mapel
                    </a>
                </div>

                <table class="w-full text-left border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">No</th>
                            <th class="p-2 border">Nama Mapel</th>
                            <th class="p-2 border">Kode</th>
                            <th class="p-2 border">Tipe</th>
                            <th class="p-2 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $i => $subject)
                            <tr>
                                <td class="p-2 border"><?= $i + 1 ?></td>
                                <td class="p-2 border"><?= e($subject->name) ?></td>
                                <td class="p-2 border"><?= e($subject->code) ?></td>
                                <td class="p-2 border">
                                    <?php $isWajib = $subject->type === 'wajib'; ?>
                                    <span class="px-2 py-1 rounded text-xs <?= $isWajib ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' ?>">
                                        <?= ucfirst($subject->type) ?>
                                    </span>
                                </td>
                                <td class="p-2 border text-center space-x-3">
                                    <a href="<?= route('admin.subjects.edit', $subject) ?>" class="text-indigo-600 hover:underline">Edit</a>
                                    <form action="<?= route('admin.subjects.destroy', $subject) ?>" method="POST" class="inline" onsubmit="return confirm('Yakin hapus mapel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data mapel.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>