<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\Question;
use App\Models\User;
use App\Models\PlotSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
  public function index()
  {
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $now  = now();

    // ===== WAJIB: berdasarkan kelas + jadwal ujian =====
    $wajib = Exam::where('type', 'wajib')
      ->where('status', 'published')
      ->whereHas('classes', fn($q) => $q->where('school_classes.id', $user->class_id))
      ->orderBy('start_at')
      ->get();

    // ===== PILIHAN: berdasarkan PLOT yang sedang aktif =====
    $grade = optional($user->schoolClass)->grade;
    $pilihan = collect();

    if (in_array($grade, ['11', '12'], true)) {

      $activePlots = PlotSession::where('start_at', '<=', $now)
        ->where('end_at', '>=', $now)
        ->where(function ($q) use ($grade) {
          $q->whereNull('grade');
          if ($grade !== null) $q->orWhere('grade', $grade);
        })
        ->pluck('plot')->unique()->toArray();

      $pilihan = collect();
      if (!empty($activePlots)) {
        $subjectIds = $user->electiveSubjects()
          ->wherePivotIn('plot', $activePlots)
          ->pluck('subjects.id')->toArray();

        if (!empty($subjectIds)) {
          $pilihan = Exam::where('type', 'pilihan')
            ->where('status', 'published')
            ->whereIn('subject_id', $subjectIds)
            ->get();
        }
      }
    }

    $exams = $wajib->concat($pilihan);
    return view('siswa.exams.index', compact('exams'));
  }

  // ===== MULAI / LANJUT UJIAN =====
  public function start(Exam $exam)
  {
    /** @var \App\Models\User $user */
    $user = auth()->user();

    if (!$this->canAccess($user, $exam)) abort(403, 'Anda tidak punya akses ke ujian ini.');
    if ($exam->status !== 'published') return back()->with('error', 'Ujian belum dipublikasikan.');

    $now = now();
    if ($exam->start_at && $now->lt($exam->start_at)) return back()->with('error', 'Ujian belum dimulai.');
    if ($exam->end_at && $now->gt($exam->end_at)) return back()->with('error', 'Ujian sudah ditutup.');

    $attempt = ExamAttempt::where('exam_id', $exam->id)->where('user_id', $user->id)->first();

    if ($attempt && $attempt->status === 'submitted') {
      return redirect()->route('siswa.exams.result', $exam);
    }

    if (!$attempt) {
      $ids = $exam->questions()->pluck('questions.id')->toArray();
      if ($exam->shuffle_questions) shuffle($ids);
      if ($exam->question_count && $exam->question_count > 0) $ids = array_slice($ids, 0, $exam->question_count);

      $attempt = ExamAttempt::create([
        'exam_id'         => $exam->id,
        'user_id'         => $user->id,
        'started_at'      => $now,
        'status'          => 'ongoing',
        'violation_count' => 0,
        'question_order'  => $ids,
      ]);
    }

    return redirect()->route('siswa.exams.work', $exam);
  }

  // ===== HALAMAN KERJAKAN =====
  public function work(Exam $exam)
  {
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $attempt = ExamAttempt::where('exam_id', $exam->id)->where('user_id', $user->id)->first();

    if (!$attempt) return redirect()->route('siswa.exams.start', $exam);
    if ($attempt->status === 'submitted') return redirect()->route('siswa.exams.result', $exam);

    $order = $attempt->question_order ?? [];
    $byId  = Question::whereIn('id', $order)->get()->keyBy('id');
    $questions = collect($order)->map(fn($id) => $byId->get($id))->filter()->values();

    $answers = $attempt->answers()->pluck('answer', 'question_id')->toArray();

    $deadline = $attempt->started_at->copy()->addMinutes((int) ($exam->duration ?? 0));
    if ($exam->end_at && $exam->end_at->lt($deadline)) $deadline = $exam->end_at;
    $remaining = max(0, now()->diffInSeconds($deadline, false));

    return view('siswa.exams.work', compact('exam', 'attempt', 'questions', 'answers', 'remaining'));
  }

  // ===== AUTOSAVE JAWABAN (AJAX) =====
  public function saveAnswer(Request $request, Exam $exam)
  {
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $attempt = ExamAttempt::where('exam_id', $exam->id)->where('user_id', $user->id)->where('status', 'ongoing')->first();
    if (!$attempt) return response()->json(['ok' => false], 403);

    $data = $request->validate([
      'question_id' => 'required|integer',
      'answer'      => 'nullable|string',
    ]);

    if (!in_array($data['question_id'], $attempt->question_order ?? [])) {
      return response()->json(['ok' => false], 422);
    }

    ExamAnswer::updateOrCreate(
      ['attempt_id' => $attempt->id, 'question_id' => $data['question_id']],
      ['answer' => $data['answer'] ?? null]
    );

    return response()->json(['ok' => true]);
  }

  // ===== KUMPULKAN =====
  public function submit(Request $request, Exam $exam)
  {
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $attempt = ExamAttempt::where('exam_id', $exam->id)->where('user_id', $user->id)->where('status', 'ongoing')->first();
    if (!$attempt) return redirect()->route('siswa.exams.index');

    $this->gradeAndFinish($attempt);

    return redirect()->route('siswa.exams.result', $exam)->with('success', 'Ujian berhasil dikumpulkan!');
  }

  // ===== CATAT PELANGGARAN (AJAX) =====
  public function violation(Request $request, Exam $exam)
  {
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $attempt = ExamAttempt::where('exam_id', $exam->id)->where('user_id', $user->id)->where('status', 'ongoing')->first();
    if (!$attempt) return response()->json(['ok' => false], 403);

    $attempt->increment('violation_count');
    $count = $attempt->violation_count;
    $limit = 3;

    if ($count >= $limit) {
      $this->gradeAndFinish($attempt);
    }

    return response()->json(['ok' => true, 'count' => $count, 'limit' => $limit, 'submitted' => $count >= $limit]);
  }

  // ===== HASIL =====
  public function result(Exam $exam)
  {
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $attempt = ExamAttempt::where('exam_id', $exam->id)->where('user_id', $user->id)->firstOrFail();

    $order     = $attempt->question_order ?? [];
    $totalSoal = count($order);
    $benar     = $attempt->answers()->where('is_correct', true)->count();
    $adaEsai   = Question::whereIn('id', $order)->whereIn('type', ['essay', 'coding'])->exists();

    return view('siswa.exams.result', compact('exam', 'attempt', 'totalSoal', 'benar', 'adaEsai'));
  }

  // ===== HELPER: nilai PG otomatis + tutup attempt =====
  private function gradeAndFinish(ExamAttempt $attempt): void
  {
    foreach ($attempt->answers as $ans) {
      $q = Question::find($ans->question_id);
      if ($q && $q->type === 'pilihan_ganda') {
        $benar = $ans->answer !== null && strtolower(trim($ans->answer)) === strtolower(trim((string) $q->correct_option));
        $ans->update(['is_correct' => $benar, 'score' => $benar ? ($q->score ?? 0) : 0]);
      }
    }
    $attempt->update([
      'score'       => $attempt->answers()->sum('score'),
      'finished_at' => now(),
      'status'      => 'submitted',
    ]);
  }

  // ===== HELPER: cek akses =====
  private function canAccess(User $user, Exam $exam): bool
  {
    if ($exam->type === 'wajib') {
      return $user->class_id && $exam->classes()->where('school_classes.id', $user->class_id)->exists();
    }

    // pilihan: harus mapel plot yang SEDANG aktif
    $now   = now();
    $grade = optional($user->schoolClass)->grade;
    if (!in_array($grade, ['11', '12'], true)) return false;

    $now = now();
    $activePlots = PlotSession::where('start_at', '<=', $now)
      ->where('end_at', '>=', $now)
      ->where(function ($q) use ($grade) {
        $q->whereNull('grade')->orWhere('grade', $grade);
      })
      ->pluck('plot')->unique()->toArray();

    if (empty($activePlots)) return false;

    return $user->electiveSubjects()
      ->wherePivotIn('plot', $activePlots)
      ->where('subjects.id', $exam->subject_id)
      ->exists();
  }
}
