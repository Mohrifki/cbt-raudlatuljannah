<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::orderBy('name')->get();
        $query = Question::with('subject')->latest();
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        $questions = $query->get();
        return view('admin.questions.index', compact('questions', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.questions.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $isPg = $request->type === 'pilihan_ganda';

        Question::create([
            'subject_id'     => $data['subject_id'],
            'created_by'     => auth()->id(),
            'type'           => $data['type'],
            'question'       => $data['question'],
            'option_a'       => $isPg ? $data['option_a'] : null,
            'option_b'       => $isPg ? $data['option_b'] : null,
            'option_c'       => $isPg ? $data['option_c'] : null,
            'option_d'       => $isPg ? $data['option_d'] : null,
            'option_e'       => $isPg ? ($data['option_e'] ?? null) : null,
            'correct_option' => $isPg ? $data['correct_option'] : null,
            'answer_key'     => $request->type === 'essay' ? ($data['answer_key'] ?? null) : null,
            'score'          => $data['score'],
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Question $question)
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.questions.edit', compact('question', 'subjects'));
    }

    public function update(Request $request, Question $question)
    {
        $data = $this->validateData($request);
        $isPg = $request->type === 'pilihan_ganda';

        $question->update([
            'subject_id'     => $data['subject_id'],
            'type'           => $data['type'],
            'question'       => $data['question'],
            'option_a'       => $isPg ? $data['option_a'] : null,
            'option_b'       => $isPg ? $data['option_b'] : null,
            'option_c'       => $isPg ? $data['option_c'] : null,
            'option_d'       => $isPg ? $data['option_d'] : null,
            'option_e'       => $isPg ? ($data['option_e'] ?? null) : null,
            'correct_option' => $isPg ? $data['correct_option'] : null,
            'answer_key'     => $request->type === 'essay' ? ($data['answer_key'] ?? null) : null,
            'score'          => $data['score'],
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Soal berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'subject_id'     => 'required|exists:subjects,id',
            'type'           => 'required|in:pilihan_ganda,essay',
            'question'       => 'required|string',
            'score'          => 'required|integer|min:1',
            'option_a'       => 'nullable|required_if:type,pilihan_ganda',
            'option_b'       => 'nullable|required_if:type,pilihan_ganda',
            'option_c'       => 'nullable|required_if:type,pilihan_ganda',
            'option_d'       => 'nullable|required_if:type,pilihan_ganda',
            'option_e'       => 'nullable',
            'correct_option' => 'nullable|required_if:type,pilihan_ganda|in:a,b,c,d,e',
            'answer_key'     => 'nullable|string',
        ], [
            'correct_option.required_if' => 'Pilih kunci jawaban untuk soal pilihan ganda.',
            'option_a.required_if'       => 'Pilihan A wajib diisi.',
            'option_b.required_if'       => 'Pilihan B wajib diisi.',
            'option_c.required_if'       => 'Pilihan C wajib diisi.',
            'option_d.required_if'       => 'Pilihan D wajib diisi.',
        ]);
    }
}