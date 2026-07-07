<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use App\Models\ExamAttempt;
use App\Models\PlotSession;
use App\Models\Setting;
use App\Exports\ExamRecapExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $exams = Exam::with('subject')->orderByDesc('start_at')->get();

        $selectedExam = null;
        $rows = collect();
        $stats = null;

        if ($request->filled('exam_id')) {
            $selectedExam = Exam::with(['subject', 'classes'])->find($request->exam_id);
            if ($selectedExam) {
                $rows  = $this->buildRecap($selectedExam);
                $stats = $this->buildStats($rows);
            }
        }

        return view('admin.reports.index', compact('exams', 'selectedExam', 'rows', 'stats'));
    }

    public function print(Exam $exam)
    {
        $exam->load(['subject', 'classes']);
        $rows  = $this->buildRecap($exam);
        $stats = $this->buildStats($rows);

        $namaSekolah    = Setting::get('nama_sekolah', 'SMA RAUDLATUL JANNAH');
        $tahunPelajaran = Setting::get('tahun_pelajaran', '2025/2026');
        $semester       = Setting::get('semester', 'Ganjil');

        $dt = Carbon::parse($exam->start_at);
        $bulanMap = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $tanggal = $dt->day . ' ' . ($bulanMap[$dt->month] ?? '') . ' ' . $dt->year;

        return view('admin.reports.print', compact('exam', 'rows', 'stats', 'namaSekolah', 'tahunPelajaran', 'semester', 'tanggal'));
    }

    public function exportExcel(Exam $exam)
    {
        $exam->load(['subject', 'classes']);
        $rows = $this->buildRecap($exam);
        $filename = 'rekap-nilai-' . Str::slug(optional($exam->subject)->name ?? 'ujian') . '.xlsx';

        return Excel::download(new ExamRecapExport($rows, 'Rekap Nilai'), $filename);
    }

    private function buildRecap(Exam $exam)
    {
        if ($exam->type === 'pilihan') {
            $activePlots = PlotSession::where('start_at', '<=', $exam->start_at)
                ->where('end_at', '>=', $exam->start_at)
                ->pluck('plot')->unique()->values()->toArray();

            $studentIds = DB::table('student_subject')
                ->where('subject_id', $exam->subject_id)
                ->whereIn('plot', $activePlots)
                ->pluck('user_id');

            $students = User::whereIn('id', $studentIds)->with('schoolClass')->orderBy('name')->get();
        } else {
            $classIds = $exam->classes->pluck('id');
            $students = User::role('siswa')->whereIn('class_id', $classIds)->with('schoolClass')->orderBy('name')->get();
        }

        $attempts = ExamAttempt::where('exam_id', $exam->id)->get()->keyBy('user_id');

        return $students->map(function ($s) use ($attempts) {
            $a = $attempts->get($s->id);
            $statusLabel = 'Belum Mengerjakan';
            if ($a) {
                $statusLabel = $a->status === 'submitted' ? 'Selesai' : 'Sedang Mengerjakan';
            }
            return (object) [
                'nis'          => $s->nis,
                'name'         => $s->name,
                'kelas'        => optional($s->schoolClass)->name,
                'status_label' => $statusLabel,
                'score'        => $a ? $a->score : null,
                'violations'   => $a ? $a->violation_count : 0,
            ];
        });
    }

    private function buildStats(\Illuminate\Support\Collection $rows)
    {
        $scores = $rows->pluck('score')->filter(fn($v) => $v !== null);

        return [
            'total'     => $rows->count(),
            'selesai'   => $rows->where('status_label', 'Selesai')->count(),
            'belum'     => $rows->where('status_label', 'Belum Mengerjakan')->count(),
            'rata'      => $scores->count() ? round($scores->avg(), 2) : 0,
            'tertinggi' => $scores->count() ? $scores->max() : 0,
            'terendah'  => $scores->count() ? $scores->min() : 0,
        ];
    }
}
