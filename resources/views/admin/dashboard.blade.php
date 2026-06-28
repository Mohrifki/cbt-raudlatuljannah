<x-admin-layout title="Dashboard">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Hello, <?= e(auth()->user()->name) ?> 👋</h2>
        <p class="text-gray-500">Selamat datang di panel admin CBT Sekolah.</p>
    </div>

    <!-- KARTU STATISTIK -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl"><i class="fa-solid fa-user-graduate"></i></div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['siswa'] ?></p>
                <p class="text-gray-500 text-sm">Total Siswa</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl"><i class="fa-solid fa-chalkboard-user"></i></div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['guru'] ?></p>
                <p class="text-gray-500 text-sm">Total Guru</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl"><i class="fa-solid fa-book"></i></div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['mapel'] ?></p>
                <p class="text-gray-500 text-sm">Total Mapel</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl"><i class="fa-solid fa-school"></i></div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['kelas'] ?></p>
                <p class="text-gray-500 text-sm">Total Kelas</p>
            </div>
        </div>
    </div>

    <!-- TABEL USER TERBARU -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-700">User Terbaru</h3>
            <a href="<?= route('admin.users.index') ?>" class="text-green-600 text-sm hover:underline">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="text-gray-500 text-sm border-b">
                    <tr>
                        <th class="py-2">Nama</th>
                        <th class="py-2">Email</th>
                        <th class="py-2">Role</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b last:border-0">
                            <td class="py-3 font-medium"><?= e($user->name) ?></td>
                            <td class="py-3 text-gray-600"><?= e($user->email) ?></td>
                            <td class="py-3">
                                <?php $role = $user->roles->pluck('name')->first(); ?>
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700"><?= e(ucfirst($role ?? '-')) ?></span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-center text-gray-500">Belum ada user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-admin-layout>