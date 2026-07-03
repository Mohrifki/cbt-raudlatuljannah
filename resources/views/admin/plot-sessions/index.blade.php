<x-admin-layout>
    <x-slot name="header">Jadwal Plot Peminatan</x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Jadwal Plot Peminatan</h1>
        <p class="text-sm text-gray-500 mb-6">Atur kapan tiap plot (1–4) berlangsung. Saat sesi aktif, siswa hanya
            melihat mapel plot tersebut.</p>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-5">
                <?= e(session('success')) ?></div>
        @endif
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm mb-5">
                <?= e($errors->first()) ?></div>
        @endif

        <!-- ============ FORM TAMBAH ============ -->
        <form method="POST" action="<?= route('admin.plot-sessions.store') ?>"
            class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
            @csrf
            <div>
                <label class="text-xs font-semibold text-gray-500">Plot</label>
                <select name="plot" class="w-full rounded-lg border-gray-200 text-sm">
                    @foreach ([1, 2, 3, 4] as $p)
                        <option value="<?= $p ?>">Plot <?= $p ?></option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500">Tingkat</label>
                <select name="grade" class="w-full rounded-lg border-gray-200 text-sm">
                    <option value="">11 & 12</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="text-xs font-semibold text-gray-500">Label (opsional)</label>
                <input type="text" name="label" placeholder="Ujian Peminatan Plot 1"
                    class="w-full rounded-lg border-gray-200 text-sm">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500">Mulai</label>
                <input type="datetime-local" name="start_at" class="w-full rounded-lg border-gray-200 text-sm">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500">Selesai</label>
                <input type="datetime-local" name="end_at" class="w-full rounded-lg border-gray-200 text-sm">
            </div>
            <div class="lg:col-span-6">
                <button
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg"><i
                        class="fa-solid fa-plus"></i> Tambah Jadwal</button>
            </div>
        </form>

        <!-- ============ DAFTAR JADWAL ============ -->
        <div class="space-y-3">
            @forelse ($sessions as $s)
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4">

                    <!-- FORM 1: UPDATE -->
                    <form method="POST" action="<?= route('admin.plot-sessions.update', $s) ?>"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                        @csrf @method('PUT')
                        <div>
                            <label class="text-xs font-semibold text-gray-500">Plot</label>
                            <select name="plot" class="w-full rounded-lg border-gray-200 text-sm">
                                @foreach ([1, 2, 3, 4] as $p)
                                    <option value="<?= $p ?>" <?= $s->plot == $p ? 'selected' : '' ?>>Plot <?= $p ?>
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500">Tingkat</label>
                            <select name="grade" class="w-full rounded-lg border-gray-200 text-sm">
                                <option value="" <?= $s->grade === null ? 'selected' : '' ?>>11 & 12</option>
                                @foreach (['11', '12'] as $g)
                                    <option value="<?= $g ?>" <?= $s->grade === $g ? 'selected' : '' ?>><?= $g ?>
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="text-xs font-semibold text-gray-500">Label</label>
                            <input type="text" name="label" value="<?= e($s->label) ?>"
                                class="w-full rounded-lg border-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500">Mulai</label>
                            <input type="datetime-local" name="start_at"
                                value="<?= optional($s->start_at)->format('Y-m-d\TH:i') ?>"
                                class="w-full rounded-lg border-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500">Selesai</label>
                            <input type="datetime-local" name="end_at"
                                value="<?= optional($s->end_at)->format('Y-m-d\TH:i') ?>"
                                class="w-full rounded-lg border-gray-200 text-sm">
                        </div>
                        <div class="lg:col-span-6">
                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg"><i
                                    class="fa-solid fa-floppy-disk"></i> Simpan</button>
                        </div>
                    </form>

                    <!-- FORM 2: HAPUS (terpisah) -->
                    <form method="POST" action="<?= route('admin.plot-sessions.destroy', $s) ?>"
                        onsubmit="return confirm('Hapus jadwal ini?')" class="mt-2">
                        @csrf @method('DELETE')
                        <button
                            class="bg-red-50 text-red-600 hover:bg-red-100 text-sm font-semibold px-4 py-2 rounded-lg"><i
                                class="fa-solid fa-trash"></i> Hapus</button>
                    </form>

                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center text-gray-400 text-sm">Belum ada
                    jadwal plot.</div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
