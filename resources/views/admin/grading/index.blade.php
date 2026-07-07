<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Penilaian Esai & Coding</h2>
    </x-slot>
    <?php $rp = request()->routeIs('guru.*') ? 'guru' : 'admin'; ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ tab: 'perlu' }">
            <div class="bg-white rounded-2xl shadow-sm p-6">

                <!-- Tab -->
                <div class="flex gap-2 mb-6">
                    <button @click="tab='perlu'"
                        :class="tab === 'perlu' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition">
                        <i class="fa-solid fa-list-check"></i> Perlu Dinilai (<?= count($perluDinilai) ?>)
                    </button>
                    <button @click="tab='selesai'"
                        :class="tab === 'selesai' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition">
                        <i class="fa-solid fa-circle-check"></i> Sudah Dinilai (<?= count($sudahDinilai) ?>)
                    </button>
                </div>

                <!-- ===== PERLU DINILAI ===== -->
                <div x-show="tab==='perlu'">
                    @if ($perluDinilai->isEmpty())
                        <div class="text-center text-gray-500 py-16">
                            <i class="fa-solid fa-circle-check text-4xl text-green-500 mb-3"></i>
                            <p class="font-medium">Tidak ada pengerjaan yang perlu dinilai.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-separate border-spacing-0">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wide">
                                        <th class="py-3 px-4 text-left font-semibold rounded-l-lg">No</th>
                                        <th class="py-3 px-4 text-left font-semibold">Siswa</th>
                                        <th class="py-3 px-4 text-left font-semibold">Ujian</th>
                                        <th class="py-3 px-4 text-left font-semibold">Waktu Submit</th>
                                        <th class="py-3 px-4 text-center font-semibold">Soal Manual</th>
                                        <th class="py-3 px-4 text-center font-semibold rounded-r-lg">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($perluDinilai as $i => $attempt)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="py-3 px-4 border-b border-gray-100 text-gray-500"><?= $i + 1 ?>
                                            </td>
                                            <td class="py-3 px-4 border-b border-gray-100 font-medium text-gray-800">
                                                <?= e(optional($attempt->user)->name ?? '-') ?></td>
                                            <td class="py-3 px-4 border-b border-gray-100 text-gray-700">
                                                <?= e(optional($attempt->exam)->title ?? '-') ?></td>
                                            <td class="py-3 px-4 border-b border-gray-100 text-gray-600">
                                                <?= e(optional($attempt->finished_at)?->format('d M Y H:i') ?? '-') ?>
                                            </td>
                                            <td class="py-3 px-4 border-b border-gray-100 text-center">
                                                <span
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold">
                                                    <i class="fa-solid fa-file-pen"></i>
                                                    <?= $attempt->perlu_dinilai_count ?> soal
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 border-b border-gray-100 text-center">
                                                <a href="<?= route($rp . '.grading.show', $attempt) ?>"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-600 text-white text-xs font-medium hover:bg-green-700 transition">
                                                    <i class="fa-solid fa-pen"></i> Nilai
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- ===== SUDAH DINILAI ===== -->
                <div x-show="tab==='selesai'" x-cloak>
                    @if ($sudahDinilai->isEmpty())
                        <div class="text-center text-gray-500 py-16">
                            <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-3"></i>
                            <p class="font-medium">Belum ada pengerjaan yang selesai dinilai.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-separate border-spacing-0">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wide">
                                        <th class="py-3 px-4 text-left font-semibold rounded-l-lg">No</th>
                                        <th class="py-3 px-4 text-left font-semibold">Siswa</th>
                                        <th class="py-3 px-4 text-left font-semibold">Ujian</th>
                                        <th class="py-3 px-4 text-left font-semibold">Waktu Submit</th>
                                        <th class="py-3 px-4 text-center font-semibold">Nilai Akhir</th>
                                        <th class="py-3 px-4 text-center font-semibold rounded-r-lg">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sudahDinilai as $i => $attempt)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="py-3 px-4 border-b border-gray-100 text-gray-500"><?= $i + 1 ?>
                                            </td>
                                            <td class="py-3 px-4 border-b border-gray-100 font-medium text-gray-800">
                                                <?= e(optional($attempt->user)->name ?? '-') ?></td>
                                            <td class="py-3 px-4 border-b border-gray-100 text-gray-700">
                                                <?= e(optional($attempt->exam)->title ?? '-') ?></td>
                                            <td class="py-3 px-4 border-b border-gray-100 text-gray-600">
                                                <?= e(optional($attempt->finished_at)?->format('d M Y H:i') ?? '-') ?>
                                            </td>
                                            <td class="py-3 px-4 border-b border-gray-100 text-center">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">
                                                    <?= (float) ($attempt->score ?? 0) ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 border-b border-gray-100 text-center">
                                                <a href="<?= route($rp . '.grading.show', $attempt) ?>"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 text-xs font-medium hover:bg-gray-200 transition">
                                                    <i class="fa-solid fa-eye"></i> Lihat / Edit
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
    </div>
</x-admin-layout>
