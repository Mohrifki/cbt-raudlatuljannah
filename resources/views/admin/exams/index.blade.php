<x-admin-layout title="Manajemen Ujian">
    <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6">
        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2 text-sm"><?= session('success') ?></div>
        @endif

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
            <h3 class="text-lg font-bold text-gray-700">Daftar Ujian</h3>
            <a href="<?= route('admin.exams.create') ?>"
                class="inline-flex items-center justify-center gap-2 bg-green-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow hover:bg-green-700 transition"><i
                    class="fa-solid fa-plus"></i> Buat Ujian</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3 border">Judul</th>
                        <th class="p-3 border">Mapel</th>
                        <th class="p-3 border">Tipe</th>
                        <th class="p-3 border">Jadwal Mulai</th>
                        <th class="p-3 border">Durasi</th>
                        <th class="p-3 border text-center">Soal</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($exams as $exam)
                        <?php
                        $typeBadge = $exam->type === 'wajib' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800';
                        $statusBadge = $exam->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600';
                        ?>
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="p-3 border font-medium text-gray-800"><?= e($exam->title) ?></td>
                            <td class="p-3 border whitespace-nowrap"><?= e($exam->subject->name ?? '-') ?></td>
                            <td class="p-3 border"><span
                                    class="px-2 py-1 rounded text-xs font-medium <?= $typeBadge ?>"><?= ucfirst($exam->type) ?></span>
                            </td>
                            <td class="p-3 border whitespace-nowrap">
                                <?= optional($exam->start_at)->format('d M Y, H:i') ?></td>
                            <td class="p-3 border whitespace-nowrap"><?= $exam->duration ?> mnt</td>
                            <td class="p-3 border text-center"><?= $exam->questions_count ?></td>
                            <td class="p-3 border"><span
                                    class="px-2 py-1 rounded text-xs font-medium <?= $statusBadge ?>"><?= ucfirst($exam->status) ?></span>
                            </td>
                            <td class="p-3 border text-center whitespace-nowrap space-x-2">
                                <a href="<?= route('admin.exams.questions', $exam) ?>"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded bg-amber-50 text-amber-600 hover:bg-amber-100"
                                    title="Kelola Soal"><i class="fa-solid fa-list-check"></i></a>
                                <a href="<?= route('admin.exams.edit', $exam) ?>"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded bg-indigo-50 text-indigo-600 hover:bg-indigo-100"
                                    title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="<?= route('admin.exams.destroy', $exam) ?>" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin hapus ujian ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-50 text-red-600 hover:bg-red-100"
                                        title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-4 text-center text-gray-500">Belum ada ujian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
