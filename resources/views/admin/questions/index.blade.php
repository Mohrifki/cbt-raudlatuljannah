<x-admin-layout title="Bank Soal">
    <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6">

        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2 text-sm"><?= session('success') ?></div>
        @endif

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
            <h3 class="text-lg font-bold text-gray-700">Daftar Soal</h3>
            <div class="flex flex-col sm:flex-row gap-2">
                <form method="GET" class="flex gap-2">
                    <select name="subject_id" onchange="this.form.submit()"
                        class="border-gray-300 rounded-lg text-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">— Semua Mapel —</option>
                        @foreach ($subjects as $subject)
                            <option value="<?= $subject->id ?>"
                                <?= request('subject_id') == $subject->id ? 'selected' : '' ?>><?= e($subject->name) ?>
                            </option>
                        @endforeach
                    </select>
                </form>
                <a href="<?= route('admin.questions.create') ?>"
                    class="inline-flex items-center justify-center gap-2 bg-green-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow hover:bg-green-700 transition">
                    <i class="fa-solid fa-plus"></i> Tambah Soal
                </a>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-3 py-1 rounded-full bg-gray-800 text-white text-xs font-medium">Total: <?= $counts->sum() ?>
                soal</span>
            @foreach ($subjects as $s)
                <span
                    class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium border border-green-200"><?= e($s->name) ?>:
                    <?= $counts[$s->id] ?? 0 ?></span>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3 border">No</th>
                        <th class="p-3 border">Mapel</th>
                        <th class="p-3 border">Soal</th>
                        <th class="p-3 border">Tipe</th>
                        <th class="p-3 border">Skor</th>
                        <th class="p-3 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($questions as $i => $q)
                        <?php
                        $isPg = $q->type === 'pilihan_ganda';
                        $badge = $isPg ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800';
                        $label = $isPg ? 'Pilihan Ganda' : 'Essay';
                        ?>
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="p-3 border"><?= $i + 1 ?></td>
                            <td class="p-3 border whitespace-nowrap"><?= e($q->subject->name ?? '-') ?></td>
                            <td class="p-3 border text-gray-700">
                                <?= e(\Illuminate\Support\Str::limit(strip_tags($q->question), 70)) ?></td>
                            <td class="p-3 border whitespace-nowrap"><span
                                    class="px-2 py-1 rounded text-xs font-medium <?= $badge ?>"><?= $label ?></span>
                            </td>
                            <td class="p-3 border text-center"><?= $q->score ?></td>
                            <td class="p-3 border text-center whitespace-nowrap space-x-2">
                                <a href="<?= route('admin.questions.edit', $q) ?>"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded bg-indigo-50 text-indigo-600 hover:bg-indigo-100"
                                    title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="<?= route('admin.questions.destroy', $q) ?>" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin hapus soal ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-50 text-red-600 hover:bg-red-100"
                                        title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">Belum ada soal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-admin-layout>
