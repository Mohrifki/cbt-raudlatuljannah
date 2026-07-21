<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;

class ResultController extends Controller
{
  /**
   * Daftar seluruh nilai siswa yang sedang login (hanya pengerjaan miliknya).
   * Menandai apakah masih ada soal essai/coding yang belum dinilai guru.
   */
  public function index()
  {
    $user = auth()->user();

    $attempts = ExamAttempt::with(['exam.subject'])
      ->where('user_id', $user->id)
      ->where('status', 'submitted')
      ->latest('finished_at')
      ->get();

    // status penilaian per attempt: true = masih ada essai/coding yang belum dinilai
    $menunggu = [];
    foreach ($attempts as $a) {
      $menunggu[$a->id] = ExamAnswer::where('attempt_id', $a->id)
        ->whereNull('score')
        ->whereHas('question', fn($q) => $q->whereIn('type', ['essay', 'coding']))
        ->exists();
    }

    return view('siswa.results.index', compact('attempts', 'menunggu'));
  }
}
