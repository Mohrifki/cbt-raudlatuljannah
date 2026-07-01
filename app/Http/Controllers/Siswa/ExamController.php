<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Model\App\Models\User;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
  public function index()
  {
    /** @var \App\Models\User $user */
    $user        = Auth::user();
    $classId     = $user->class_id;
    $electiveIds = $user->electiveSubjects()->pluck('subjects.id')->all();

    $exams = Exam::where('status', 'published')
      ->where(function ($q) use ($classId, $electiveIds) {
        // Mapel wajib -> untuk kelas siswa
        $q->where(function ($w) use ($classId) {
          $w->where('type', 'wajib')
            ->whereHas('classes', fn($c) => $c->where('school_classes.id', $classId));
        })
          // Mapel pilihan -> sesuai peminatan siswa
          ->orWhere(function ($p) use ($electiveIds) {
            $p->where('type', 'pilihan')
              ->whereIn('subject_id', $electiveIds ?: [0]);
          });
      })
      ->with('subject')
      ->orderBy('start_at')
      ->get();

    // Ambil attempt siswa (untuk status: belum / sedang / selesai)
    $attempts = ExamAttempt::where('user_id', $user->id)
      ->whereIn('exam_id', $exams->pluck('id'))
      ->get()
      ->keyBy('exam_id');

    return view('siswa.exams.index', compact('exams', 'attempts'));
  }
}
