<x-admin-layout title="Dashboard">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Hello, <?= e(auth()->user()->name) ?> 👋</h2>
        <p class="text-gray-500">Selamat datang di panel admin CBT Sekolah.</p>
    </div>

    <!-- KARTU STATISTIK -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl"><i
                    class="fa-solid fa-user-graduate"></i></div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['siswa'] ?></p>
                <p class="text-gray-500 text-sm">Total Siswa</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl"><i
                    class="fa-solid fa-chalkboard-user"></i></div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['guru'] ?></p>
                <p class="text-gray-500 text-sm">Total Guru</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl"><i
                    class="fa-solid fa-book"></i></div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['mapel'] ?></p>
                <p class="text-gray-500 text-sm">Total Mapel</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-school"></i>
            </div>
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
                                <span
                                    class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700"><?= e(ucfirst($role ?? '-')) ?></span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">Belum ada user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6 space-y-6">
        
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-green-600"></i>
            <h2 class="text-lg font-bold text-gray-800">Statistik Ujian</h2>
        </div>
        
        <!-- Kartu ringkasan statistik -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Total Ujian</p>
                <p class="text-2xl font-bold text-gray-800"><?= (int) $totalUjian ?></p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Ujian Selesai</p>
                <p class="text-2xl font-bold text-gray-800"><?= (int) $totalSelesai ?></p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Sedang Mengerjakan</p>
                <p class="text-2xl font-bold text-gray-800"><?= (int) $sedangKerja ?></p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Rata-rata Nilai</p>
                <p class="text-2xl font-bold text-gray-800"><?= $rataNilai ?></p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Total Pelanggaran</p>
                <p class="text-2xl font-bold text-gray-800"><?= (int) $totalLanggar ?></p>
            </div>
        </div>
        
        <!-- Grafik -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-3 text-sm">Partisipasi Siswa</h3>
                <div class="h-44"><canvas id="dashPartisipasi"></canvas></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-3 text-sm">Distribusi Nilai</h3>
                <div class="h-44"><canvas id="dashDistribusi"></canvas></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-3 text-sm">Rata-rata Nilai per Mapel</h3>
                <div class="h-44"><canvas id="dashMapel"></canvas></div>
            </div>
        </div>
        
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        (function() {
            const hijau = '#16a34a',
                biru = '#3b82f6',
                indigo = '#6366f1',
                amber = '#f59e0b',
                merah = '#ef4444',
                abu = '#e5e7eb';
            new Chart(document.getElementById('dashPartisipasi'), {
                type: 'doughnut',
                data: {
                    labels: ['Sudah Mengerjakan', 'Belum Mengerjakan'],
                    datasets: [{
                        data: [<?= (int) $siswaSudah ?>, <?= (int) $siswaBelum ?>],
                        backgroundColor: [hijau, abu]
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            new Chart(document.getElementById('dashDistribusi'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_keys($buckets)) ?>,
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: <?= json_encode(array_values($buckets)) ?>,
                        backgroundColor: [hijau, biru, indigo, amber, merah]
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            new Chart(document.getElementById('dashMapel'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode($mapelLabels) ?>,
                    datasets: [{
                        label: 'Rata-rata Nilai',
                        data: <?= json_encode($mapelData) ?>,
                        backgroundColor: biru
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        })();
    </script>

</x-admin-layout>
