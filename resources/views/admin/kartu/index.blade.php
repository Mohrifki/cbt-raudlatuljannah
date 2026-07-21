<x-admin-layout title="Kartu Ujian">
    <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="fa-solid fa-id-card text-green-600"></i>
            <h2 class="text-lg font-bold text-gray-800">Cetak Kartu Ujian per Kelas</h2>
        </div>

        <!-- Pengaturan kop kartu (fleksibel) -->
        <div class="border border-gray-200 rounded-lg p-4 mb-5 bg-gray-50">
            <p class="text-sm font-semibold text-gray-700 mb-3"><i class="fa-solid fa-sliders text-green-600 mr-1"></i> Pengaturan Kop Kartu</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Ujian</label>
                    <input id="f_jenis" type="text" value="PENILAIAN SUMATIF AKHIR SEMESTER" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tahun Pelajaran</label>
                    <input id="f_tahun" type="text" value="2026/2027" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Sesi Ujian</label>
                    <input id="f_sesi" type="text" value="Sesi 1" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Ruang</label>
                    <input id="f_ruang" type="text" value="-" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Kepala Sekolah</label>
                    <input id="f_kepsek" type="text" value="Lisya Romadloniyah, S.S, M.Pd." class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Isi data di atas, lalu klik "Cetak Kartu" pada kelas yang diinginkan. Jendela cetak akan muncul otomatis.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($kelasList as $k)
                <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between hover:shadow-sm">
                    <div>
                        <p class="font-bold text-gray-800"><?= e($k->name) ?></p>
                        <p class="text-xs text-gray-500"><?= (int) $k->jumlah_siswa ?> siswa</p>
                    </div>
                    <button type="button" onclick="cetakKartu('<?= route('admin.kartu.print', $k) ?>')"
                        class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-3 py-2 rounded">
                        <i class="fa-solid fa-print"></i> Cetak Kartu
                    </button>
                </div>
            @empty
                <p class="text-gray-400 col-span-full text-center py-6">Belum ada kelas.</p>
            @endforelse
        </div>
    </div>

    <script>
        function cetakKartu(baseUrl) {
            var p = new URLSearchParams();
            p.set('jenis', document.getElementById('f_jenis').value);
            p.set('tahun', document.getElementById('f_tahun').value);
            p.set('sesi', document.getElementById('f_sesi').value);
            p.set('ruang', document.getElementById('f_ruang').value);
            p.set('kepsek', document.getElementById('f_kepsek').value);
            var url = baseUrl + (baseUrl.indexOf('?') === -1 ? '?' : '&') + p.toString();
            var w = window.open(url, 'cetakKartu', 'width=1024,height=720,scrollbars=yes,resizable=yes');
            if (w) { w.focus(); } else { alert('Popup diblokir browser. Mohon izinkan popup untuk situs ini.'); }
        }
    </script>
</x-admin-layout>
