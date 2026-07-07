<x-admin-layout>
    <div class="space-y-6">
        <!-- Sapaan -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Halo, <?= e($u->name) ?></h2>
            <p class="text-gray-500 text-sm">Selamat datang di panel guru CBT Sekolah.</p>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-file-circle-question"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800"><?= $mySoal ?></div>
                    <div class="text-sm text-gray-500">Soal Saya</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800"><?= $myExamsCount ?></div>
                    <div class="text-sm text-gray-500">Ujian Saya</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800"><?= $perluDinilai ?></div>
                    <div class="text-sm text-gray-500">Perlu Dinilai</div>
                </div>
            </div>
        </div>

        <!-- Ujian hari ini -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Ujian Hari Ini</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 text-xs uppercase tracking-wide border-b border-gray-100">
                            <th class="text-left py-2 px-3">Mata Pelajaran</th>
                            <th class="text-left py-2 px-3">Tipe</th>
                            <th class="text-left py-2 px-3">Waktu Mulai</th>
                            <th class="text-left py-2 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($todayExams as $e)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-2 px-3 font-medium text-gray-800"><?= e($e->title ?? optional($e->subject)->name) ?></td>
                                <td class="py-2 px-3">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold <?= $e->type === 'pilihan' ? 'bg-purple-50 text-purple-700' : 'bg-sky-50 text-sky-700' ?>">
                                        <?= $e->type === 'pilihan' ? 'Peminatan' : 'Wajib' ?>
                                    </span>
                                </td>
                                <td class="py-2 px-3 text-gray-600"><?= e(\Carbon\Carbon::parse($e->start_at)->format('H:i')) ?></td>
                                <td class="py-2 px-3">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold <?= $e->status === 'published' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' ?>">
                                        <?= ucfirst($e->status) ?>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 text-center text-gray-500">Tidak ada ujian hari ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
