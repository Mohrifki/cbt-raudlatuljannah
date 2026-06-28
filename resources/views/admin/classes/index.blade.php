<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Manajemen Kelas</h2>
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
                    <h3 class="text-lg font-bold text-gray-700">Daftar Kelas</h3>
                    <a href="<?= route('admin.classes.create') ?>"
                       class="inline-block bg-green-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow-md hover:bg-green-700 transition">
                        + Tambah Kelas
                    </a>
                </div>

                <table class="w-full text-left border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">No</th>
                            <th class="p-2 border">Nama Kelas</th>
                            <th class="p-2 border">Tingkat</th>
                            <th class="p-2 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $i => $class)
                            <tr>
                                <td class="p-2 border"><?= $i + 1 ?></td>
                                <td class="p-2 border"><?= e($class->name) ?></td>
                                <td class="p-2 border"><?= e($class->grade) ?></td>
                                <td class="p-2 border text-center space-x-3">
                                    <a href="<?= route('admin.classes.edit', $class) ?>" class="text-indigo-600 hover:underline">Edit</a>
                                    <form action="<?= route('admin.classes.destroy', $class) ?>" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">Belum ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>