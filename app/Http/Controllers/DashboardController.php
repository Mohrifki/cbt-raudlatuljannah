<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Models\ExamViolation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $u */
        $u = auth()->user();

        if ($u->hasRole('admin')) {
            return redirect('/admin/dashboard');
        }
        if ($u->hasRole('siswa')) {
            return redirect('/siswa/dashboard');
        }
        if ($u->hasRole('guru')) {
            return redirect('/guru/dashboard');
        }

        abort(403);
    }

    public function admin()
    {
        $stats = [
            'siswa' => User::role('siswa')->count(),
            'guru'  => User::role('guru')->count(),
            'mapel' => Subject::count(),
            'kelas' => SchoolClass::count(),
        ];
        $users = User::with('roles')->latest()->take(8)->get();

        // ===== Data statistik untuk grafik dashboard =====
        $submitted = ExamAttempt::where('status', 'submitted')->with('exam.subject')->get();

        $totalUjian   = Exam::count();
        $totalSelesai = $submitted->count();
        $sedangKerja  = ExamAttempt::where('status', '!=', 'submitted')->count();
        $rataNilai    = $totalSelesai > 0 ? round($submitted->avg('score'), 1) : 0;
        $totalLanggar = ExamViolation::count();

        // Distribusi nilai (bucket huruf)
        $buckets = ['A (90-100)' => 0, 'B (80-89)' => 0, 'C (70-79)' => 0, 'D (60-69)' => 0, 'E (0-59)' => 0];
        foreach ($submitted as $a) {
            $s = (float) $a->score;
            if ($s >= 90) { $buckets['A (90-100)']++; }
            elseif ($s >= 80) { $buckets['B (80-89)']++; }
            elseif ($s >= 70) { $buckets['C (70-79)']++; }
            elseif ($s >= 60) { $buckets['D (60-69)']++; }
            else { $buckets['E (0-59)']++; }
        }

        // Rata-rata nilai per mapel
        $perMapel = $submitted
            ->groupBy(fn($a) => optional(optional($a->exam)->subject)->name ?: 'Lainnya')
            ->map(fn($grp) => round($grp->avg('score'), 1));
        $mapelLabels = $perMapel->keys()->all();
        $mapelData   = array_values($perMapel->all());

        // Partisipasi keseluruhan
        $sudahIds   = ExamAttempt::where('status', 'submitted')->distinct()->pluck('user_id')->all();
        $siswaSudah = User::role('siswa')->whereIn('id', $sudahIds ?: [0])->count();
        $siswaBelum = max(0, $stats['siswa'] - $siswaSudah);

        return view('admin.dashboard', compact(
            'stats', 'users',
            'totalUjian', 'totalSelesai', 'sedangKerja', 'rataNilai', 'totalLanggar',
            'buckets', 'mapelLabels', 'mapelData', 'siswaSudah', 'siswaBelum'
        ));
    }

    public function guru()
    {
        /** @var \App\Models\User $u */
        $u = auth()->user();

        $hasCreator = Schema::hasColumn('exams', 'created_by');

        $mySoal = Question::where('created_by', $u->id)->count();

        $examQuery = Exam::query();
        if ($hasCreator) {
            $examQuery->where('created_by', $u->id);
        }

        $myExamsCount = (clone $examQuery)->count();
        $myExamIds    = (clone $examQuery)->pluck('id');

        $todayExams = (clone $examQuery)
            ->whereDate('start_at', today())
            ->with('subject')
            ->orderBy('start_at')
            ->get();

        $perluDinilai = ExamAttempt::whereIn('exam_id', $myExamIds)
            ->where('status', 'submitted')
            ->whereHas('answers', function ($q) {
                $q->whereNull('score')
                  ->whereHas('question', fn($qq) => $qq->whereIn('type', ['essay', 'coding']));
            })
            ->count();

        return view('guru.dashboard', compact('u', 'mySoal', 'myExamsCount', 'todayExams', 'perluDinilai'));
    }

    public function siswa()
    {
        /** @var \App\Models\User $user */
        $user        = Auth::user();
        $classId     = $user->class_id;
        $electiveIds = $user->electiveSubjects()->pluck('subjects.id')->all();

        $exams = Exam::where('status', 'published')
            ->where(function ($q) use ($classId, $electiveIds) {
                $q->where(function ($w) use ($classId) {
                    $w->where('type', 'wajib')
                        ->whereHas('classes', fn($c) => $c->where('school_classes.id', $classId));
                })->orWhere(function ($p) use ($electiveIds) {
                    $p->where('type', 'pilihan')
                        ->whereIn('subject_id', $electiveIds ?: [0]);
                });
            })
            ->with('subject')
            ->orderBy('start_at')
            ->get();

        $attempts = ExamAttempt::where('user_id', $user->id)->get()->keyBy('exam_id');

        $total   = $exams->count();
        $selesai = $exams->filter(fn($e) => ($attempts[$e->id]->status ?? null) === 'submitted')->count();
        $belum   = $total - $selesai;

        return view('siswa.dashboard', compact('user', 'exams', 'attempts', 'total', 'selesai', 'belum'));
    }
}