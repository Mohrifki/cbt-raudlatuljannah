<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;

class GradingController extends Controller
{
  /**
   * Penilaian di panel guru DIBATASI hanya untuk pengerjaan pada ujian
   * yang dibuat oleh guru yang login (exam.created_by).
   */
  public function index()
  {
    $uid = auth()->id();

    // Perlu dinilai: ada jawaban essay/coding yang skornya belum diisi
    $perluDinilai = ExamAttempt::with(['user', 'exam'])
      ->where('status', 'submitted')
      ->whereHas('exam', fn($q) => $q->where('created_by', $uid))
      ->whereHas('answers', function ($q) {
        $q->whereNull('score')
          ->whereHas('question', fn($qq) => $qq->whereIn('type', ['essay', 'coding']));
      })
      ->withCount(['answers as perlu_dinilai_count' => function ($q) {
        $q->whereNull('score')
          ->whereHas('question', fn($qq) => $qq->whereIn('type', ['essay', 'coding']));
      }])
      ->latest('finished_at')
      ->get();

    // Sudah dinilai: punya soal essay/coding, dan tidak ada lagi yang kosong skornya
    $sudahDinilai = ExamAttempt::with(['user', 'exam'])
      ->whereHas('exam', fn($q) => $q->where('created_by', $uid))
      ->whereHas(
        'answers',
        fn($q) =>
        $q->whereHas('question', fn($qq) => $qq->whereIn('type', ['essay', 'coding']))
      )
      ->whereDoesntHave('answers', function ($q) {
        $q->whereNull('score')
          ->whereHas('question', fn($qq) => $qq->whereIn('type', ['essay', 'coding']));
      })
      ->latest('finished_at')
      ->get();

    return view('admin.grading.index', compact('perluDinilai', 'sudahDinilai'));
  }

  public function show(ExamAttempt $attempt)
  {
    $this->authorizeOwner($attempt);
    $attempt->load(['user', 'exam']);

    $answers = $attempt->answers()
      ->with('question')
      ->whereHas('question', fn($q) => $q->whereIn('type', ['essay', 'coding']))
      ->get();

    return view('admin.grading.show', compact('attempt', 'answers'));
  }

  public function update(Request $request, ExamAttempt $attempt)
  {
    $this->authorizeOwner($attempt);

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

    return redirect()->route('guru.grading.index')
      ->with('success', 'Nilai berhasil disimpan.');
  }

  /**
   * Pastikan pengerjaan ini berasal dari ujian milik guru yang login.
   */
  private function authorizeOwner(ExamAttempt $attempt): void
  {
    abort_if(optional($attempt->exam)->created_by !== auth()->id(), 403, 'Anda tidak berhak menilai pengerjaan ini.');
  }

  private function recalculate(ExamAttempt $attempt)
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
