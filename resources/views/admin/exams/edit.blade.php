<x-admin-layout title="Edit Ujian">
    <div class="max-w-3xl mx-auto">
        <a href="<?= route('admin.exams.index') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 mb-4"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-file-pen"></i></div>
                <div><h2 class="text-white font-bold text-lg">Edit Ujian</h2><p class="text-green-50 text-sm">Perbarui paket ujian</p></div>
            </div>
            <form action="<?= route('admin.exams.update', $exam) ?>" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')
                @include('admin.exams._form')
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="<?= route('admin.exams.index') ?>" class="bg-gray-100 text-gray-700 font-semibold px-5 py-2.5 rounded-lg hover:bg-gray-200">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 shadow"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>