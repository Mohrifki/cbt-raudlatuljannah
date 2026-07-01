<x-admin-layout title="Import Siswa">
    <div class="max-w-xl mx-auto">
        <a href="<?= route('admin.users.index') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar User</a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5">
                <h2 class="text-white font-bold text-lg"><i class="fa-solid fa-file-import"></i> Import Siswa dari Excel</h2>
                <p class="text-green-50 text-sm">Format: .xlsx, .xls, atau .csv</p>
            </div>

            <div class="p-6 space-y-5">
                @if (session('success'))
                    <div class="rounded bg-green-100 text-green-800 px-4 py-2 text-sm"><?= session('success') ?></div>
                @endif
                @if ($errors->any())
                    <div class="rounded bg-red-100 text-red-800 px-4 py-2 text-sm"><?= $errors->first() ?></div>
                @endif

                <div class="rounded-lg bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 text-sm space-y-1">
                    <p class="font-semibold">Langkah:</p>
                    <ol class="list-decimal ml-5 space-y-0.5">
                        <li>Unduh template di bawah.</li>
                        <li>Isi kolom: <b>name, email, password, nis, kelas, peminatan</b>.</li>
                        <li>Kolom <b>kelas</b> diisi nama kelas persis (mis. <b>X-A</b>).</li>
                        <li>Kolom <b>peminatan</b> opsional, pisah koma (mis. <i>Informatika X, Biologi X</i>).</li>
                        <li><b>password</b> boleh kosong → default <b>password</b>.</li>
                    </ol>
                </div>

                <a href="<?= route('admin.users.import.template') ?>" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 font-medium px-4 py-2 rounded-lg hover:bg-gray-200 text-sm"><i class="fa-solid fa-download"></i> Unduh Template Excel</a>

                <form action="<?= route('admin.users.import.store') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih File</label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="w-full border-gray-300 rounded-lg shadow-sm text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-600 file:text-white hover:file:bg-green-700">
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 shadow"><i class="fa-solid fa-upload"></i> Import Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>