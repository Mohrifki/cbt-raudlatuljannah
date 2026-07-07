<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamController extends Controller
{
  /**
   * Semua ujian di panel guru DIBATASI hanya milik guru yang login
   * (kolom created_by). Guru tidak bisa melihat/mengubah ujian guru lain.
   */
  public function index()
  {
    $exams = Exam::where('created_by', auth()->id())
      ->with(['subject', 'classes'])
      ->withCount('questions')
      ->latest()
      ->get();
    return view('admin.exams.index', compact('exams'));
  }

  public function create()
  {
    $subjects = Subject::orderBy('name')->get();
    $classes = SchoolClass::orderBy('name')->get();
    return view('admin.exams.create', compact('subjects', 'classes'));
  }

  public function store(Request $request)
  {
    $data = $this->validateData($request);
    $exam = Exam::create([
      'title'             => $data['title'],
      'grade'             => $data['grade'] ?? null,
      'subject_id'        => $data['subject_id'],
      'description'       => $data['description'] ?? null,
      'type'              => $data['type'],
      'duration'          => $data['duration'],
      'start_at'          => $data['start_at'],
      'end_at'            => $data['end_at'],
      'shuffle_questions' => $request->boolean('shuffle_questions'),
      'shuffle_options'   => $request->boolean('shuffle_options'),
      'status'            => $data['status'],
      'created_by'        => auth()->id(),
    ]);

    if ($data['type'] === 'wajib') {
      $exam->classes()->sync($request->target_classes ?? []);
    }

    return redirect()->route('guru.exams.index')->with('success', 'Ujian berhasil dibuat. Selanjutnya tambahkan soal ke paket ini.');
  }

  public function edit(Exam $exam)
  {
    $this->authorizeOwner($exam);
    $subjects = Subject::orderBy('name')->get();
    $classes = SchoolClass::orderBy('name')->get();
    $selectedClasses = $exam->classes->pluck('id')->all();
    return view('admin.exams.edit', compact('exam', 'subjects', 'classes', 'selectedClasses'));
  }

  public function update(Request $request, Exam $exam)
  {
    $this->authorizeOwner($exam);
    $data = $this->validateData($request);
    $exam->update([
      'title'             => $data['title'],
      'grade'             => $data['grade'] ?? null,
      'subject_id'        => $data['subject_id'],
      'description'       => $data['description'] ?? null,
      'type'              => $data['type'],
      'duration'          => $data['duration'],
      'start_at'          => $data['start_at'],
      'end_at'            => $data['end_at'],
      'shuffle_questions' => $request->boolean('shuffle_questions'),
      'shuffle_options'   => $request->boolean('shuffle_options'),
      'status'            => $data['status'],
    ]);

    $exam->classes()->sync($data['type'] === 'wajib' ? ($request->target_classes ?? []) : []);

    return redirect()->route('guru.exams.index')->with('success', 'Ujian berhasil diperbarui.');
  }

  public function destroy(Exam $exam)
  {
    $this->authorizeOwner($exam);
    $exam->delete();
    return redirect()->route('guru.exams.index')->with('success', 'Ujian berhasil dihapus.');
  }

  public function questions(Request $request, Exam $exam)
  {
    $this->authorizeOwner($exam);
    $grade = $request->query('grade', (string) ($exam->grade ?? '')); // default: ikut tingkat ujian

    $query = Question::where('subject_id', $exam->subject_id);
    if ($grade !== '' && $grade !== null) {
      $query->where(function ($q) use ($grade) {
        $q->where('grade', $grade)->orWhereNull('grade');
      });
    }
    $questions = $query->latest()->get();

    $selected = $exam->questions->pluck('id')->all();
    $totalForSubject = Question::where('subject_id', $exam->subject_id)->count();

    return view('admin.exams.questions', compact('exam', 'questions', 'selected', 'grade', 'totalForSubject'));
  }

  public function syncQuestions(Request $request, Exam $exam)
  {
    $this->authorizeOwner($exam);
    $data = $request->validate([
      'question_ids'    => 'nullable|array',
      'question_ids.*'  => 'exists:questions,id',
      'question_count'  => 'nullable|integer|min:1',
    ]);

    $ids = $data['question_ids'] ?? [];

    if (($data['question_count'] ?? 0) > count($ids)) {
      return back()->withErrors(['question_count' => 'Jumlah soal acak tidak boleh melebihi jumlah soal yang dipilih.'])->withInput();
    }

    $sync = [];
    foreach (array_values($ids) as $i => $qid) {
      $sync[$qid] = ['order' => $i + 1];
    }
    $exam->questions()->sync($sync);
    $exam->update(['question_count' => $data['question_count'] ?? null]);

    return redirect()->route('guru.exams.questions', $exam)->with('success', count($ids) . ' soal disimpan ke paket ujian.');
  }

  /**
   * Pastikan ujian milik guru yang sedang login.
   */
  private function authorizeOwner(Exam $exam): void
  {
    abort_if($exam->created_by !== auth()->id(), 403, 'Anda tidak berhak mengakses ujian ini.');
  }

  private function validateData(Request $request): array
  {
    return $request->validate([
      'title'           => 'required|string|max:255',
      'grade'           => 'nullable|in:10,11,12',
      'subject_id'      => 'required|exists:subjects,id',
      'description'     => 'nullable|string',
      'type'            => 'required|in:wajib,pilihan',
      'duration'        => 'required|integer|min:1',
      'start_at'        => 'required|date',
      'end_at'          => 'required|date|after:start_at',
      'status'          => 'required|in:draft,published',
      'target_classes'  => 'nullable|array|required_if:type,wajib',
      'target_classes.*' => 'exists:school_classes,id',
    ], [
      'end_at.after'             => 'Waktu selesai harus setelah waktu mulai.',
      'target_classes.required_if' => 'Pilih minimal satu kelas target untuk ujian wajib.',
    ]);
  }
}