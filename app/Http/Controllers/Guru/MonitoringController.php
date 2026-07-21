<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\Question;
use App\Models\ExamViolation;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
  /** Pastikan ujian milik guru yang sedang login. */
  private function authorizeOwner(Exam $exam): void
  {
    abort_if($exam->created_by !== auth()->id(), 403);
  }

  /**
   * Daftar ujian MILIK guru yang punya aktivitas peserta (sedang / sudah mengerjakan).
   */
  public function index()
  {
    $uid = auth()->id();
    $ownedIds = Exam::where('created_by', $uid)->pluck('id');

    $ongoingCounts = ExamAttempt::where('status', 'ongoing')
      ->whereIn('exam_id', $ownedIds)
      ->selectRaw('exam_id, count(*) as c')
      ->groupBy('exam_id')
      ->pluck('c', 'exam_id');

    $submittedCounts = ExamAttempt::where('status', 'submitted')
      ->whereIn('exam_id', $ownedIds)
      ->selectRaw('exam_id, count(*) as c')
      ->groupBy('exam_id')
      ->pluck('c', 'exam_id');

    $allExamIds = $ongoingCounts->keys()->merge($submittedCounts->keys())->unique();

    $exams = Exam::with('subject')
      ->whereIn('id', $allExamIds)
      ->get()
      ->sortByDesc(fn($e) => (int) ($ongoingCounts[$e->id] ?? 0))
      ->values();

    return view('admin.monitoring.index', compact('exams', 'ongoingCounts', 'submittedCounts'));
  }

  /**
   * Detail live sebuah ujian milik guru: peserta, status, progres, & pelanggaran.
   */
  public function show(Exam $exam)
  {
    $this->authorizeOwner($exam);
    $exam->load('subject');

    $attempts = ExamAttempt::with('user')
      ->where('exam_id', $exam->id)
      ->orderByRaw("CASE WHEN status = 'ongoing' THEN 0 ELSE 1 END")
      ->orderByDesc('started_at')
      ->get();

    $answered = ExamAnswer::whereIn('attempt_id', $attempts->pluck('id'))
      ->whereNotNull('answer')
      ->where('answer', '!=', '')
      ->selectRaw('attempt_id, count(*) as c')
      ->groupBy('attempt_id')
      ->pluck('c', 'attempt_id');

    return view('admin.monitoring.show', compact('exam', 'attempts', 'answered'));
  }

  /**
   * Detail pengerjaan satu siswa pada ujian milik guru.
   */
  public function attempt(ExamAttempt $attempt)
  {
    $attempt->load(['user', 'exam.subject']);
    abort_if(optional($attempt->exam)->created_by !== auth()->id(), 403);

    $order = is_array($attempt->question_order) ? $attempt->question_order : [];
    $questionsById = Question::whereIn('id', $order)->get()->keyBy('id');
    $answersByQ = $attempt->answers()->get()->keyBy('question_id');

    $items = collect($order)
      ->map(fn($id) => (object) [
        'question' => $questionsById->get($id),
        'answer'   => $answersByQ->get($id),
      ])
      ->filter(fn($x) => $x->question)
      ->values();

    $violations = ExamViolation::where('attempt_id', $attempt->id)
      ->orderBy('created_at')
      ->get();

    return view('admin.monitoring.attempt', compact('attempt', 'items', 'violations'));
  }

  /** Simpan nilai esai/coding — hanya untuk ujian milik guru sendiri. */
  public function grade(Request $request, ExamAttempt $attempt)
  {
    abort_if(optional($attempt->exam)->created_by !== auth()->id(), 403);

    $data = $request->validate([
      'scores'   => 'required|array',
      'scores.*' => 'nullable|numeric|min:0',
    ]);

    foreach ($data['scores'] as $answerId => $score) {
      $answer = ExamAnswer::where('attempt_id', $attempt->id)->find($answerId);
      if (!$answer) continue;

      $answer->score      = $score;
      $answer->is_correct = ($score === null) ? null : ((float) $score > 0);
      $answer->save();
    }

    $this->recalculate($attempt);

    return redirect()->route('guru.monitoring.attempt', $attempt)
      ->with('success', 'Nilai esai berhasil disimpan.');
  }

  private function recalculate(ExamAttempt $attempt): void
  {
    $answers  = $attempt->answers()->with('question')->get();
    $totalMax = 0;
    $totalGot = 0;

    foreach ($answers as $a) {
      $totalMax += (float) (optional($a->question)->score ?? 0);
      $totalGot += (float) ($a->score ?? 0);
    }

    $attempt->score = $totalMax > 0 ? round($totalGot / $totalMax * 100, 2) : 0;
    $attempt->save();
  }
}