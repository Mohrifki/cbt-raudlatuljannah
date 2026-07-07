<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Hadir Ujian</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm p-6">

                <form method="GET" class="flex items-end gap-3 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Ujian</label>
                        <input type="date" name="date" value="<?= e($date) ?>"
                            class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 text-sm">
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                        <i class="fa-solid fa-magnifying-glass"></i> Tampilkan
                    </button>
                </form>

                @if ($exams->isEmpty())
                    <div class="text-center text-gray-500 py-16">
                        <i class="fa-solid fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
                        <p class="font-medium">Tidak ada ujian pada tanggal ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-separate border-spacing-0">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wide">
                                    <th class="py-3 px-4 text-left font-semibold rounded-l-lg">No</th>
                                    <th class="py-3 px-4 text-left font-semibold">Mata Pelajaran</th>
                                    <th class="py-3 px-4 text-left font-semibold">Tipe</th>
                                    <th class="py-3 px-4 text-left font-semibold">Kelas / Peminatan</th>
                                    <th class="py-3 px-4 text-left font-semibold">Jam</th>
                                    <th class="py-3 px-4 text-center font-semibold rounded-r-lg">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($exams as $i => $exam)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4 border-b border-gray-100 text-gray-500"><?= $i + 1 ?></td>
                                        <td class="py-3 px-4 border-b border-gray-100 font-medium text-gray-800">
                                            <?= e(optional($exam->subject)->name ?? '-') ?></td>
                                        <td class="py-3 px-4 border-b border-gray-100">
                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold <?= $exam->type === 'pilihan' ? 'bg-purple-50 text-purple-700' : 'bg-sky-50 text-sky-700' ?>">
                                                <?= $exam->type === 'pilihan' ? 'Peminatan' : 'Wajib' ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border-b border-gray-100 text-gray-700">
                                            <?= $exam->type === 'pilihan' ? 'Peminatan' : e($exam->classes->pluck('name')->join(', ') ?: '-') ?>
                                        </td>
                                        <td class="py-3 px-4 border-b border-gray-100 text-gray-600">
                                            <?= e(\Carbon\Carbon::parse($exam->start_at)->format('H:i')) ?></td>
                                        <td class="py-3 px-4 border-b border-gray-100 text-center">
                                            <a href="<?= route('admin.attendance.print', $exam) ?>" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-600 text-white text-xs font-medium hover:bg-green-700 transition">
                                                <i class="fa-solid fa-print"></i> Cetak Daftar Hadir
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-admin-layout>
