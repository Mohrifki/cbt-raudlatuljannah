<x-admin-layout title="Statistik">
    <div class="space-y-6">

        <!-- KARTU RINGKASAN -->
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Total Siswa</p>
                <p class="text-2xl font-bold text-gray-800"><?= (int) $totalSiswa ?></p>
                <i class="fa-solid fa-users text-green-500"></i>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Total Ujian</p>
                <p class="text-2xl font-bold text-gray-800"><?= (int) $totalUjian ?></p>
                <i class="fa-solid fa-clipboard-list text-blue-500"></i>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Ujian Selesai</p>
                <p class="text-2xl font-bold text-gray-800"><?= (int) $totalSelesai ?></p>
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Sedang Mengerjakan</p>
                <p class="text-2xl font-bold text-gray-800"><?= (int) $sedangKerja ?></p>
                <i class="fa-solid fa-spinner text-amber-500"></i>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Rata-rata Nilai</p>
                <p class="text-2xl font-bold text-gray-800"><?= $rataNilai ?></p>
                <i class="fa-solid fa-chart-line text-indigo-500"></i>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Total Pelanggaran</p>
                <p class="text-2xl font-bold text-gray-800"><?= (int) $totalLanggar ?></p>
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
            </div>
        </div>

        <!-- BARIS GRAFIK 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-chart-pie text-green-500"></i> Partisipasi Siswa</h3>
                <div class="max-w-xs mx-auto"><canvas id="chartPartisipasi"></canvas></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-chart-simple text-indigo-500"></i> Distribusi Nilai</h3>
                <canvas id="chartDistribusi"></canvas>
            </div>
        </div>

        <!-- BARIS GRAFIK 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-book text-blue-500"></i> Rata-rata Nilai per Mapel</h3>
                <canvas id="chartMapel"></canvas>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-chalkboard-user text-amber-500"></i> Partisipasi per Kelas</h3>
                <canvas id="chartKelas"></canvas>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        (function () {
            const hijau = '#16a34a', biru = '#3b82f6', indigo = '#6366f1', amber = '#f59e0b', merah = '#ef4444', abu = '#e5e7eb';

            // Partisipasi siswa (doughnut)
            new Chart(document.getElementById('chartPartisipasi'), {
                type: 'doughnut',
                data: {
                    labels: ['Sudah Mengerjakan', 'Belum Mengerjakan'],
                    datasets: [{ data: [<?= (int) $siswaSudah ?>, <?= (int) $siswaBelum ?>], backgroundColor: [hijau, abu] }]
                },
                options: { plugins: { legend: { position: 'bottom' } } }
            });

            // Distribusi nilai (bar)
            new Chart(document.getElementById('chartDistribusi'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_keys($buckets)) ?>,
                    datasets: [{ label: 'Jumlah Siswa', data: <?= json_encode(array_values($buckets)) ?>, backgroundColor: [hijau, biru, indigo, amber, merah] }]
                },
                options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
            });

            // Rata-rata per mapel (bar horizontal)
            new Chart(document.getElementById('chartMapel'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode($mapelLabels) ?>,
                    datasets: [{ label: 'Rata-rata Nilai', data: <?= json_encode($mapelData) ?>, backgroundColor: biru }]
                },
                options: { indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, max: 100 } } }
            });

            // Partisipasi per kelas (bar bertumpuk)
            new Chart(document.getElementById('chartKelas'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode($kelasLabels) ?>,
                    datasets: [
                        { label: 'Sudah', data: <?= json_encode($kelasSudah) ?>, backgroundColor: hijau },
                        { label: 'Belum', data: <?= json_encode($kelasBelum) ?>, backgroundColor: abu }
                    ]
                },
                options: { plugins: { legend: { position: 'bottom' } }, scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } } } }
            });
        })();
    </script>
</x-admin-layout>
