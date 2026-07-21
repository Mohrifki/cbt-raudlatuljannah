<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class KartuController extends Controller
{
    // Halaman pilih kelas
    public function index()
    {
        $kelasList = SchoolClass::orderBy('grade')->orderBy('name')->get();
        // Hitung jumlah siswa dengan query yang PERSIS SAMA dengan saat cetak,
        // supaya angka di menu selalu cocok dengan isi kartu.
        foreach ($kelasList as $k) {
            $k->jumlah_siswa = User::role('siswa')->where('class_id', $k->id)->count();
        }
        return view('admin.kartu.index', compact('kelasList'));
    }

    // Cetak kartu untuk semua siswa di satu kelas
    public function print(Request $request, SchoolClass $kelas)
    {
        $siswa = User::role('siswa')
            ->where('class_id', $kelas->id)
            ->orderBy('name')
            ->get();

        // Identitas kop — fleksibel via query string dari halaman menu Kartu Ujian.
        $sekolah     = 'RAUDLATUL JANNAH';
        $jenisUjian  = strtoupper(trim((string) $request->query('jenis'))) ?: 'PENILAIAN SUMATIF AKHIR SEMESTER';
        $tahunAjaran = trim((string) $request->query('tahun')) ?: '2026/2027';
        $sesi        = trim((string) $request->query('sesi')) ?: 'Sesi 1';
        $ruang       = trim((string) $request->query('ruang')) ?: '-';
        $kepsek      = trim((string) $request->query('kepsek')) ?: 'Lisya Romadloniyah, S.S, M.Pd.';
        $nip         = trim((string) $request->query('nip'));

        return view('admin.kartu.print', compact(
            'kelas', 'siswa', 'sekolah', 'jenisUjian', 'tahunAjaran', 'sesi', 'ruang', 'kepsek', 'nip'
        ));
    }
}
