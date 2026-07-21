<x-admin-layout title="Import Soal">
    <?php $rp = request()->routeIs('guru.*') ? 'guru' : 'admin'; ?>
    <div class="max-w-2xl mx-auto">
        <a href="<?= route($rp . '.questions.index') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i class="fa-solid fa-arrow-left"></i> Kembali ke Bank Soal</a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-file-import"></i></div>
                <div>
                    <h2 class="text-white font-bold text-lg">Import Soal</h2>
                    <p class="text-green-50 text-sm">Tambah banyak soal sekaligus via Excel</p>
                </div>
            </div>

            <div class="p-6 space-y-6">
                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 text-red-700 px-4 py-3 text-sm">
                        @foreach ($errors->all() as $err)
                            <div><?= e($err) ?></div>
                        @endforeach
                    </div>
                @endif

                <div class="rounded-lg bg-blue-50 border border-blue-100 p-4">
                    <p class="text-sm text-blue-800 font-medium mb-2"><i class="fa-solid fa-circle-info"></i> Langkah import:</p>
                    <ol class="list-decimal list-inside text-sm text-blue-700 space-y-1">
                        <li>Download template Excel di bawah.</li>
                        <li>Isi soal sesuai kolom (jangan ubah baris judul).</li>
                        <li>Upload file yang sudah diisi.</li>
                    </ol>
                    <a href="<?= route($rp . '.questions.import.template') ?>" class="inline-flex items-center gap-2 mt-3 bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-700"><i class="fa-solid fa-download"></i> Download Template</a>
                </div>

                <form action="<?= route($rp . '.questions.import') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">File Excel / CSV</label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:bg-green-50 file:text-green-700 file:font-semibold hover:file:bg-green-100">
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 shadow"><i class="fa-solid fa-upload"></i> Import Sekarang</button>
                </form>

                <div class="border-t pt-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Panduan kolom:</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs border border-gray-200">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr><th class="p-2 border text-left">Kolom</th><th class="p-2 border text-left">Keterangan</th></tr>
                            </thead>
                            <tbody class="text-gray-600">
                                <tr><td class="p-2 border font-medium">Kode Mapel</td><td class="p-2 border">Harus cocok dengan kode mapel yang ada (mis. MTK, INF)</td></tr>
                                <tr><td class="p-2 border font-medium">Tingkat</td><td class="p-2 border"><code>10</code>, <code>11</code>, atau <code>12</code> (boleh <code>X</code>/<code>XI</code>/<code>XII</code>). Kosongkan = semua tingkat</td></tr>
                                <tr><td class="p-2 border font-medium">Tipe</td><td class="p-2 border"><code>pg</code>, <code>essay</code>, atau <code>coding</code></td></tr>
                                <tr><td class="p-2 border font-medium">Pertanyaan</td><td class="p-2 border">Teks soal (wajib)</td></tr>
                                <tr><td class="p-2 border font-medium">Opsi A–E</td><td class="p-2 border">Khusus tipe <code>pg</code></td></tr>
                                <tr><td class="p-2 border font-medium">Kunci</td><td class="p-2 border">Khusus <code>pg</code>: a / b / c / d / e</td></tr>
                                <tr><td class="p-2 border font-medium">Kunci Jawaban</td><td class="p-2 border">Khusus <code>essay</code> (opsional)</td></tr>
                                <tr><td class="p-2 border font-medium">Bahasa / Kode Awal</td><td class="p-2 border">Khusus <code>coding</code></td></tr>
                                <tr><td class="p-2 border font-medium">Skor</td><td class="p-2 border">Angka, default 1</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>