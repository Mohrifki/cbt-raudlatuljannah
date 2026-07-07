<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use App\Models\PlotSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());

        $exams = Exam::with(['subject', 'classes'])
            ->whereDate('start_at', $date)
            ->orderBy('start_at')
            ->get();

        return view('admin.attendance.index', compact('exams', 'date'));
    }

    public function print(Exam $exam)
    {
        $exam->load(['subject', 'classes']);

        if ($exam->type === 'pilihan') {
            // Plot yang aktif pada waktu ujian
            $activePlots = PlotSession::where('start_at', '<=', $exam->start_at)
                ->where('end_at', '>=', $exam->start_at)
                ->pluck('plot')->unique()->values()->toArray();

            $studentIds = DB::table('student_subject')
                ->where('subject_id', $exam->subject_id)
                ->whereIn('plot', $activePlots)
                ->pluck('user_id');

            $students = User::whereIn('id', $studentIds)
                ->with('schoolClass')->orderBy('name')->get();

            $kelasLabel = 'Peminatan';
        } else {
            $classIds = $exam->classes->pluck('id');

            $students = User::role('siswa')
                ->whereIn('class_id', $classIds)
                ->with('schoolClass')->orderBy('name')->get();

            $kelasLabel = $exam->classes->pluck('name')->join(', ');
        }

        // Format tanggal Indonesia
        $dt = Carbon::parse($exam->start_at);
        $hariMap  = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $bulanMap = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $hari    = $hariMap[$dt->format('l')] ?? $dt->format('l');
        $tanggal = $dt->day . ' ' . ($bulanMap[$dt->month] ?? '') . ' ' . $dt->year;
        $jam     = $dt->format('H:i') . ' - ' . Carbon::parse($exam->end_at)->format('H:i');

        $namaSekolah    = \App\Models\Setting::get('nama_sekolah', 'SMA RAUDLATUL JANNAH');
        $tahunPelajaran = \App\Models\Setting::get('tahun_pelajaran', '2025/2026');

        $modeUjian = \App\Models\Setting::get('mode_ujian', 'PTS');
        $semester  = \App\Models\Setting::get('semester', 'Ganjil');

        $modeMap = [
            'PTS'  => 'PENILAIAN TENGAH SEMESTER',
            'SAS'  => 'SUMATIF AKHIR SEMESTER',
            'USEK' => 'UJIAN SEKOLAH',
        ];
        $modeFull = $modeMap[$modeUjian] ?? $modeUjian;

        return view('admin.attendance.print', compact('exam', 'students', 'kelasLabel', 'hari', 'tanggal', 'jam', 'namaSekolah', 'tahunPelajaran', 'modeUjian', 'modeFull', 'semester'));
    }
}
