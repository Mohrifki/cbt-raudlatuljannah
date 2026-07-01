<x-admin-layout title="Kelas">
    <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6">

        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2 text-sm"><?= session('success') ?></div>
        @endif

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
            <h3 class="text-lg font-bold text-gray-700">Daftar Kelas</h3>
            <a href="<?= route('admin.classes.create') ?>"
                class="inline-flex items-center justify-center gap-2 bg-green-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow hover:bg-green-700 transition">
                <i class="fa-solid fa-plus"></i> Tambah Kelas
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3 border">No</th>
                        <th class="p-3 border">Nama Kelas</th>
                        <th class="p-3 border">Tingkat</th>
                        <th class="p-3 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classes as $i => $class)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border"><?= $i + 1 ?></td>
                            <td class="p-3 border font-medium text-gray-800"><?= e($class->name) ?></td>
                            <td class="p-3 border"><span
                                    class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800"><?= e($class->grade) ?></span>
                            </td>
                            <td class="p-3 border text-center whitespace-nowrap space-x-2">
                                <a href="<?= route('admin.classes.students', $class) ?>"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded bg-emerald-50 text-emerald-600 hover:bg-emerald-100"
                                    title="Kelola Siswa"><i class="fa-solid fa-users"></i></a>
                                <a href="<?= route('admin.classes.edit', $class) ?>"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded bg-indigo-50 text-indigo-600 hover:bg-indigo-100"
                                    title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="<?= route('admin.classes.destroy', $class) ?>" method="POST"
                                    class="inline" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-50 text-red-600 hover:bg-red-100"
                                        title="Hapus"><i class="fa-solid fa-trash"></i></button>
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
</x-admin-layout>
