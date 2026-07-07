<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan & Rekap Nilai</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <form method="GET" class="flex items-end gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Ujian</label>
                        <select name="exam_id"
                            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 text-sm">
                            <option value="">-- Pilih Ujian --</option>
                            @foreach ($exams as $ex)
                                <option value="<?= $ex->id ?>"
                                    <?= optional($selectedExam)->id == $ex->id ? 'selected' : '' ?>>
                                    <?= e(optional($ex->subject)->name ?? 'Ujian') ?> —
                                    <?= e(\Carbon\Carbon::parse($ex->start_at)->format('d/m/Y')) ?>
                                    (<?= $ex->type === 'pilihan' ? 'Peminatan' : 'Wajib' ?>)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                        <i class="fa-solid fa-magnifying-glass"></i> Tampilkan
                    </button>
                </form>
            </div>

            @if ($selectedExam)
                <!-- Statistik -->
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="text-xs text-gray-500">Total Peserta</div>
                        <div class="text-xl font-bold text-gray-800"><?= $stats['total'] ?></div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="text-xs text-gray-500">Selesai</div>
                        <div class="text-xl font-bold text-green-600"><?= $stats['selesai'] ?></div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="text-xs text-gray-500">Belum</div>
                        <div class="text-xl font-bold text-amber-600"><?= $stats['belum'] ?></div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="text-xs text-gray-500">Rata-rata</div>
                        <div class="text-xl font-bold text-gray-800"><?= $stats['rata'] ?></div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="text-xs text-gray-500">Tertinggi</div>
                        <div class="text-xl font-bold text-gray-800"><?= $stats['tertinggi'] ?></div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="text-xs text-gray-500">Terendah</div>
                        <div class="text-xl font-bold text-gray-800"><?= $stats['terendah'] ?></div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800"><?= e(optional($selectedExam->subject)->name) ?></h3>
                        <div class="flex gap-2">
                            <a href="<?= route('admin.reports.excel', $selectedExam) ?>"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700">
                                <i class="fa-solid fa-file-excel"></i> Export Excel
                            </a>
                            <a href="<?= route('admin.reports.print', $selectedExam) ?>" target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-700 text-white text-xs font-medium hover:bg-gray-800">
                                <i class="fa-solid fa-print"></i> Cetak / PDF
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-separate border-spacing-0">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wide">
                                    <th class="py-3 px-4 text-left font-semibold rounded-l-lg">No</th>
                                    <th class="py-3 px-4 text-left font-semibold">NIS</th>
                                    <th class="py-3 px-4 text-left font-semibold">Nama Siswa</th>
                                    <th class="py-3 px-4 text-left font-semibold">Kelas</th>
                                    <th class="py-3 px-4 text-left font-semibold">Status</th>
                                    <th class="py-3 px-4 text-center font-semibold">Nilai</th>
                                    <th class="py-3 px-4 text-center font-semibold rounded-r-lg">Pelanggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $i => $r)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4 border-b border-gray-100 text-gray-500"><?= $i + 1 ?></td>
                                        <td class="py-3 px-4 border-b border-gray-100 text-gray-700">
                                            <?= e($r->nis ?? '-') ?></td>
                                        <td class="py-3 px-4 border-b border-gray-100 font-medium text-gray-800">
                                            <?= e($r->name) ?></td>
                                        <td class="py-3 px-4 border-b border-gray-100 text-gray-700">
                                            <?= e($r->kelas ?? '-') ?></td>
                                        <td class="py-3 px-4 border-b border-gray-100">
                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold <?= $r->status_label === 'Selesai' ? 'bg-green-50 text-green-700' : ($r->status_label === 'Sedang Mengerjakan' ? 'bg-sky-50 text-sky-700' : 'bg-gray-100 text-gray-500') ?>">
                                                <?= e($r->status_label) ?>
                                            </span>
                                        </td>
                                        <td
                                            class="py-3 px-4 border-b border-gray-100 text-center font-bold text-gray-800">
                                            <?= $r->score !== null ? e($r->score) : '-' ?></td>
                                        <td class="py-3 px-4 border-b border-gray-100 text-center text-gray-600">
                                            <?= (int) $r->violations ?></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-10 text-center text-gray-500">Tidak ada peserta.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-admin-layout>
