<x-admin-layout title="Kelola Siswa Kelas">
    <div class="max-w-3xl mx-auto">
        <a href="<?= route('admin.classes.index') ?>"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i
                class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kelas</a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5">
                <h2 class="text-white font-bold text-lg"><i class="fa-solid fa-users"></i> Kelola Siswa</h2>
                <p class="text-green-50 text-sm">Kelas: <?= e($class->name) ?></p>
            </div>

            <div class="p-6 space-y-5">
                @if (session('success'))
                    <div class="rounded bg-green-100 text-green-800 px-4 py-2 text-sm"><?= session('success') ?></div>
                @endif

                @if ($students->isEmpty())
                    <div class="rounded-lg bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 text-sm">
                        Belum ada data siswa. Tambahkan siswa dulu di menu <a href="<?= route('admin.users.index') ?>"
                            class="underline font-medium">Manajemen User</a>.
                    </div>
                @else
                    <input type="text" id="search-siswa" placeholder="Cari nama siswa..."
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">

                    <form action="<?= route('admin.classes.students.sync', $class) ?>" method="POST" class="space-y-4">
                        @csrf
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Centang siswa yang masuk kelas
                                <b><?= e($class->name) ?></b></span>
                            <span class="text-sm text-gray-500">Dipilih: <b id="sel-count">0</b></span>
                        </div>

                        <div class="border border-gray-200 rounded-lg divide-y max-h-[28rem] overflow-y-auto">
                            @foreach ($students as $s)
                                <?php
                                if ($s->class_id === $class->id) {
                                    $tag = 'bg-green-100 text-green-800';
                                    $tagText = 'Kelas ini';
                                } elseif (!$s->class_id) {
                                    $tag = 'bg-gray-100 text-gray-600';
                                    $tagText = 'Belum ada kelas';
                                } else {
                                    $tag = 'bg-amber-100 text-amber-800';
                                    $tagText = 'Sekarang: ' . ($s->schoolClass->name ?? '-');
                                }
                                ?>
                                <label class="siswa-row flex items-center gap-3 p-3 hover:bg-gray-50 cursor-pointer"
                                    data-name="<?= e(strtolower($s->name)) ?>">
                                    <input type="checkbox" name="student_ids[]" value="<?= $s->id ?>"
                                        class="s-check rounded text-green-600 focus:ring-green-500"
                                        <?= in_array($s->id, old('student_ids', $current)) ? 'checked' : '' ?>>
                                    <span class="flex-1">
                                        <span class="text-sm font-medium text-gray-800"><?= e($s->name) ?></span>
                                        <span
                                            class="text-xs text-gray-400 ml-1"><?= $s->nis ? '(NIS: ' . e($s->nis) . ')' : '' ?></span>
                                    </span>
                                    <span
                                        class="px-2 py-0.5 rounded text-xs font-medium <?= $tag ?>"><?= $tagText ?></span>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex justify-end pt-3 border-t">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 shadow"><i
                                    class="fa-solid fa-floppy-disk"></i> Simpan</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        (function() {
            const boxes = document.querySelectorAll('.s-check');
            const counter = document.getElementById('sel-count');
            const search = document.getElementById('search-siswa');

            function update() {
                if (counter) counter.textContent = document.querySelectorAll('.s-check:checked').length;
            }
            boxes.forEach(b => b.addEventListener('change', update));
            update();
            if (search) search.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.siswa-row').forEach(row => {
                    row.style.display = row.dataset.name.includes(q) ? 'flex' : 'none';
                });
            });
        })();
    </script>
</x-admin-layout>
