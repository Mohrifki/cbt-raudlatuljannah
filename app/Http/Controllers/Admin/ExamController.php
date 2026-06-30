<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with(['subject', 'classes'])->withCount('questions')->latest()->get();
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

        return redirect()->route('admin.exams.index')->with('success', 'Ujian berhasil dibuat. Selanjutnya tambahkan soal ke paket ini.');
    }

    public function edit(Exam $exam)
    {
        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $selectedClasses = $exam->classes->pluck('id')->all();
        return view('admin.exams.edit', compact('exam', 'subjects', 'classes', 'selectedClasses'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $this->validateData($request);
        $exam->update([
            'title'             => $data['title'],
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

        return redirect()->route('admin.exams.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success', 'Ujian berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title'           => 'required|string|max:255',
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