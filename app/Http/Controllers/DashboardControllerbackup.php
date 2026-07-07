<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\Auth;

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
        return view('admin.dashboard', compact('stats', 'users'));
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
