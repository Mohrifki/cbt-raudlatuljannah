<x-admin-layout title="Tambah User">
    <div class="max-w-2xl mx-auto">
        <a href="<?= route('admin.users.index') ?>"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i
                class="fa-solid fa-arrow-left"></i> Kembali ke Daftar User</a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl"><i
                        class="fa-solid fa-user-plus"></i></div>
                <div>
                    <h2 class="text-white font-bold text-lg">Tambah User</h2>
                    <p class="text-green-50 text-sm">Buat akun admin, guru, atau siswa</p>
                </div>
            </div>

            <form action="<?= route('admin.users.store') ?>" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="<?= old('name') ?>"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500"
                        placeholder="Nama lengkap">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1"><?= $message ?></p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="<?= old('email') ?>"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500"
                            placeholder="email@sekolah.sch.id">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1"><?= $message ?></p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                        <select name="role" id="role-select"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="guru" <?= old('role') === 'guru' ? 'selected' : '' ?>>Guru</option>
                            <option value="siswa" <?= old('role', 'siswa') === 'siswa' ? 'selected' : '' ?>>Siswa
                            </option>
                        </select>
                        @error('role')
                            <p class="text-red-600 text-sm mt-1"><?= $message ?></p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <input type="password" name="password"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-red-600 text-sm mt-1"><?= $message ?></p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500"
                            placeholder="Ulangi password">
                    </div>
                </div>

                <!-- ====== DATA SISWA (muncul saat role = Siswa) ====== -->
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto Siswa</label>
                    <div class="flex items-center gap-4">
                        <?php $fotoLama = $user->photo ?? null ? asset('storage/' . $user->photo) : null; ?>
                        <img id="preview-foto"
                            src="<?= $fotoLama ?: 'https://placehold.co/80x80/e2e8f0/94a3b8?text=Foto' ?>"
                            class="w-20 h-20 rounded-xl object-cover border border-gray-200">
                        <input type="file" name="photo" accept="image/*"
                            onchange="document.getElementById('preview-foto').src = window.URL.createObjectURL(this.files[0])"
                            class="text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-600 file:text-white hover:file:bg-green-700">
                    </div>
                    @error('photo')
                        <p class="text-red-600 text-sm mt-1"><?= $message ?></p>
                    @enderror
                </div>
                <div id="siswa-fields" x-data="{ grade: '' }" x-init="$nextTick(() => grade = $refs.kelas?.selectedOptions[0]?.dataset.grade || '')"
                    class="rounded-xl border border-dashed border-green-300 bg-green-50/50 p-4">
                    <p class="text-sm font-semibold text-green-700 mb-3 flex items-center gap-2"><i
                            class="fa-solid fa-graduation-cap"></i> Data Siswa</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">NIS</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i
                                        class="fa-solid fa-id-card"></i></span>
                                <input type="text" name="nis" value="<?= old('nis') ?>"
                                    class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500"
                                    placeholder="Nomor Induk Siswa">
                            </div>
                            @error('nis')
                                <p class="text-red-600 text-sm mt-1"><?= $message ?></p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i
                                        class="fa-solid fa-school"></i></span>
                                <select name="class_id" x-ref="kelas"
                                    x-on:change="grade = $event.target.selectedOptions[0]?.dataset.grade || ''"
                                    class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 ...">
                                    <option value="">— Pilih Kelas —</option>
                                    @foreach ($classes as $class)
                                        <option value="<?= $class->id ?>"
                                            <?= old('class_id') == $class->id ? 'selected' : '' ?>>
                                            <?= e($class->name) ?> (<?= e($class->grade) ?>)</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('class_id')
                                <p class="text-red-600 text-sm mt-1"><?= $message ?></p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5" x-show="grade === 'XI' || grade === 'XII'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-list-check text-green-600"></i> Mapel Pilihan (Peminatan)</label>
                        @if ($electives->isEmpty())
                            <p class="text-sm text-gray-400">Belum ada mapel bertipe "pilihan". Tambahkan dulu di menu
                                Mata Pelajaran.</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach ($electives as $elective)
                                    <label
                                        class="flex items-center gap-2 px-3 py-2 bg-white border rounded-lg cursor-pointer hover:bg-green-50">
                                        <input type="checkbox" name="elective_subjects[]"
                                            value="<?= $elective->id ?>"
                                            <?= in_array($elective->id, old('elective_subjects', [])) ? 'checked' : '' ?>
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700"><?= e($elective->name) ?></span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <!-- ====== /DATA SISWA ====== -->

                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="<?= route('admin.users.index') ?>"
                        class="bg-gray-100 text-gray-700 font-semibold px-5 py-2.5 rounded-lg hover:bg-gray-200">Batal</a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 shadow"><i
                            class="fa-solid fa-floppy-disk"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function() {
            const roleSel = document.getElementById('role-select');
            const siswaFields = document.getElementById('siswa-fields');

            function toggle() {
                siswaFields.style.display = (roleSel.value === 'siswa') ? 'block' : 'none';
            }
            roleSel.addEventListener('change', toggle);
            toggle(); // set kondisi awal
        })();
    </script>
</x-admin-layout>
