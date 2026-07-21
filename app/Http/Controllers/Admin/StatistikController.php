<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamViolation;

class StatistikController extends Controller
{
    public function index()
    {
        // Semua percobaan yang sudah dikumpulkan (nilai skala 0-100)
        $submitted = ExamAttempt::where('status', 'submitted')->with('exam.subject')->get();

        // ===== Ringkasan =====
        $totalSiswa   = User::role('siswa')->count();
        $totalUjian   = Exam::count();
        $totalSelesai = $submitted->count();
        $sedangKerja  = ExamAttempt::where('status', '!=', 'submitted')->count();
        $rataNilai    = $totalSelesai > 0 ? round($submitted->avg('score'), 1) : 0;
        $totalLanggar = ExamViolation::count();

        // ===== Distribusi nilai (bucket huruf) =====
        $buckets = [
            'A (90-100)' => 0,
            'B (80-89)'  => 0,
            'C (70-79)'  => 0,
            'D (60-69)'  => 0,
            'E (0-59)'   => 0,
        ];
        foreach ($submitted as $a) {
            $s = (float) $a->score;
            if ($s >= 90) {
                $buckets['A (90-100)']++;
            } elseif ($s >= 80) {
                $buckets['B (80-89)']++;
            } elseif ($s >= 70) {
                $buckets['C (70-79)']++;
            } elseif ($s >= 60) {
                $buckets['D (60-69)']++;
            } else {
                $buckets['E (0-59)']++;
            }
        }

        // ===== Rata-rata nilai per mapel =====
        $perMapel = $submitted
            ->groupBy(fn($a) => optional(optional($a->exam)->subject)->name ?: 'Lainnya')
            ->map(fn($grp) => round($grp->avg('score'), 1));
        $mapelLabels = $perMapel->keys()->all();
        $mapelData   = array_values($perMapel->all());

        // ===== Partisipasi per kelas =====
        $sudahIds = ExamAttempt::where('status', 'submitted')->distinct()->pluck('user_id')->all();
        $kelasList = SchoolClass::orderBy('grade')->orderBy('name')->get();
        $kelasLabels = [];
        $kelasSudah  = [];
        $kelasBelum  = [];
        foreach ($kelasList as $k) {
            $total = User::role('siswa')->where('class_id', $k->id)->count();
            $sudah = User::role('siswa')->where('class_id', $k->id)->whereIn('id', $sudahIds ?: [0])->count();
            $kelasLabels[] = $k->name;
            $kelasSudah[]  = $sudah;
            $kelasBelum[]  = max(0, $total - $sudah);
        }

        // ===== Partisipasi keseluruhan =====
        $siswaSudah = User::role('siswa')->whereIn('id', $sudahIds ?: [0])->count();
        $siswaBelum = max(0, $totalSiswa - $siswaSudah);

        return view('admin.statistik.index', compact(
            'totalSiswa', 'totalUjian', 'totalSelesai', 'sedangKerja', 'rataNilai', 'totalLanggar',
            'buckets', 'mapelLabels', 'mapelData', 'kelasLabels', 'kelasSudah', 'kelasBelum',
            'siswaSudah', 'siswaBelum'
        ));
    }
}
