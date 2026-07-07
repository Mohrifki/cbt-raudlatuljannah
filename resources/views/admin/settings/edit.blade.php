<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan Sekolah</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm p-6">

                @if (session('success'))
                    <div class="mb-5 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
                        <i class="fa-solid fa-circle-check"></i> <?= e(session('success')) ?>
                    </div>
                @endif

                <form method="POST" action="<?= route('admin.settings.update') ?>" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Sekolah</label>
                        <input type="text" name="nama_sekolah"
                            value="<?= e(old('nama_sekolah', $settings['nama_sekolah'])) ?>"
                            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 text-sm">
                        @error('nama_sekolah')
                            <p class="text-red-600 text-xs mt-1"><?= e($message) ?></p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun Pelajaran</label>
                        <input type="text" name="tahun_pelajaran"
                            value="<?= e(old('tahun_pelajaran', $settings['tahun_pelajaran'])) ?>"
                            placeholder="Contoh: 2025/2026"
                            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 text-sm">
                        @error('tahun_pelajaran')
                            <p class="text-red-600 text-xs mt-1"><?= e($message) ?></p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Mode Ujian</label>
                        <select name="mode_ujian"
                            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 text-sm">
                            <option value="PTS" <?= $settings['mode_ujian'] === 'PTS' ? 'selected' : '' ?>>PTS -
                                Penilaian Tengah Semester</option>
                            <option value="SAS" <?= $settings['mode_ujian'] === 'SAS' ? 'selected' : '' ?>>SAS -
                                Sumatif Akhir Semester</option>
                            <option value="USEK" <?= $settings['mode_ujian'] === 'USEK' ? 'selected' : '' ?>>USEK
                                - Ujian Sekolah (khusus Kelas XII)</option>
                        </select>
                        @error('mode_ujian')
                            <p class="text-red-600 text-xs mt-1"><?= e($message) ?></p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Semester</label>
                        <select name="semester"
                            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 text-sm">
                            <option value="Ganjil" <?= $settings['semester'] === 'Ganjil' ? 'selected' : '' ?>>Ganjil
                            </option>
                            <option value="Genap" <?= $settings['semester'] === 'Genap' ? 'selected' : '' ?>>Genap
                            </option>
                        </select>
                        @error('semester')
                            <p class="text-red-600 text-xs mt-1"><?= e($message) ?></p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-admin-layout>
