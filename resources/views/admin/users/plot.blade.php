<x-admin-layout>
    <div class="max-w-xl mx-auto py-8 px-4">
        <a href="<?= route('admin.users.index') ?>" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-3 mb-1">Atur Plot Peminatan</h1>
        <p class="text-sm text-gray-500 mb-6">Siswa: <span class="font-semibold text-gray-700"><?= e($user->name) ?></span></p>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm mb-5"><?= e($errors->first()) ?></div>
        @endif

        <form method="POST" action="<?= route('admin.users.plot.store', $user) ?>" class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 space-y-4">
            @csrf @method('PUT')
            @foreach ([1,2,3,4] as $p)
                <div>
                    <label class="text-sm font-semibold text-gray-700">Plot <?= $p ?></label>
                    <select name="plot_<?= $p ?>" class="w-full mt-1 rounded-lg border-gray-200 text-sm">
                        <option value="">— Tidak ada —</option>
                        @foreach ($subjects as $subj)
                            <option value="<?= $subj->id ?>" <?= (($current[$p] ?? null) == $subj->id) ? 'selected' : '' ?>><?= e($subj->name) ?></option>
                        @endforeach
                    </select>
                </div>
            @endforeach
            <button class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl"><i class="fa-solid fa-floppy-disk"></i> Simpan Plot</button>
        </form>
    </div>
</x-admin-layout>