<x-admin-layout title="Edit User">
    <div class="max-w-3xl mx-auto">
        <a href="<?= route('admin.users.index') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i class="fa-solid fa-arrow-left"></i> Kembali ke daftar user</a>

        <?php $currentRole = $user->getRoleNames()->first(); ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-user-pen"></i></div>
                <div>
                    <h2 class="text-white font-bold text-lg">Edit User</h2>
                    <p class="text-green-50 text-sm"><?= e($user->name) ?></p>
                </div>
            </div>

            <form action="<?= route('admin.users.update', $user) ?>" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="name" value="<?= old('name', $user->name) ?>" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                        @error('name')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" value="<?= old('email', $user->email) ?>" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                        @error('email')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-gray-400 font-normal">(kosongkan jika tetap)</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="••••••••">
                        </div>
                        @error('password')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-user-shield"></i></span>
                            <select name="role" id="role-select" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                                @foreach ($roles as $role)
                                    <option value="<?= $role ?>" <?= old('role', $currentRole) === $role ? 'selected' : '' ?>><?= ucfirst($role) ?></option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                    </div>
                </div>

                <div id="siswa-fields" class="rounded-xl border border-dashed border-green-300 bg-green-50/50 p-4">
                    <p class="text-sm font-semibold text-green-700 mb-3 flex items-center gap-2"><i class="fa-solid fa-graduation-cap"></i> Data Siswa</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">NIS</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-id-card"></i></span>
                                <input type="text" name="nis" value="<?= old('nis', $user->nis) ?>" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Nomor Induk Siswa">
                            </div>
                            @error('nis')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-school"></i></span>
                                <select name="class_id" class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="">— Pilih Kelas —</option>
                                    @foreach ($classes as $class)
                                        <option value="<?= $class->id ?>" <?= old('class_id', $user->class_id) == $class->id ? 'selected' : '' ?>><?= e($class->name) ?> (<?= e($class->grade) ?>)</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('class_id')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="<?= route('admin.users.index') ?>" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 font-semibold px-5 py-2.5 rounded-lg hover:bg-gray-200">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 shadow"><i class="fa-solid fa-floppy-disk"></i> Update User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            var role = document.getElementById('role-select');
            var box = document.getElementById('siswa-fields');
            function toggle() { box.style.display = (role.value === 'siswa') ? '' : 'none'; }
            if (role && box) { toggle(); role.addEventListener('change', toggle); }
        })();
    </script>
</x-admin-layout>