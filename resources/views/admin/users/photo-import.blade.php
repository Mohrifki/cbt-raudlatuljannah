<x-admin-layout title="Import Foto Siswa">
    <div class="max-w-xl mx-auto">
        <a href="<?= route('admin.users.index') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i class="fa-solid fa-arrow-left"></i> Kembali</a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5">
                <h2 class="text-white font-bold text-lg"><i class="fa-solid fa-images"></i> Import Foto Siswa (via NIS)</h2>
            </div>
            <div class="p-6 space-y-5">
                @if ($errors->any())
                    <div class="rounded bg-red-100 text-red-800 px-4 py-2 text-sm"><?= $errors->first() ?></div>
                @endif

                <div class="rounded-lg bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 text-sm space-y-1">
                    <p class="font-semibold">Cara pakai:</p>
                    <ol class="list-decimal ml-5 space-y-0.5">
                        <li>Beri nama tiap file foto <b>sesuai NIS</b> siswa. Contoh: <b>2024001.jpg</b></li>
                        <li>Pilih banyak file sekaligus (boleh puluhan).</li>
                        <li>Sistem otomatis mencocokkan foto ke siswa berdasarkan NIS.</li>
                    </ol>
                </div>

                <form action="<?= route('admin.users.photos.import.store') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Foto (bisa banyak)</label>
                        <input type="file" name="photos[]" accept="image/*" multiple required class="w-full border-gray-300 rounded-lg shadow-sm text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-600 file:text-white hover:file:bg-green-700">
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 shadow"><i class="fa-solid fa-upload"></i> Import Foto</button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>