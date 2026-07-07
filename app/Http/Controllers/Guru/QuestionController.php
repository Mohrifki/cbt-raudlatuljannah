<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Semua query soal di panel guru DIBATASI hanya milik guru yang login
     * (kolom created_by). Guru tidak bisa melihat/mengubah soal guru lain.
     */
    public function index(Request $request)
    {
        $uid = auth()->id();

        // Hitung jumlah soal milik guru ini per mapel
        $counts = Question::where('created_by', $uid)
            ->selectRaw('subject_id, COUNT(*) as total')
            ->groupBy('subject_id')
            ->pluck('total', 'subject_id');

        // Hanya tampilkan mapel yang benar-benar sudah punya soal milik guru ini
        $subjects = Subject::whereIn('id', $counts->keys())->orderBy('name')->get();

        $query = Question::with('subject')->where('created_by', $uid)->latest();
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        $questions = $query->get();

        return view('admin.questions.index', compact('questions', 'subjects', 'counts'));
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
            'grade'          => $request->input('grade') ?: null,
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
            'language'       => $request->type === 'coding' ? ($data['language'] ?? null) : null,
            'starter_code'   => $request->type === 'coding' ? ($data['starter_code'] ?? null) : null,
            'score'          => $data['score'],
        ]);

        return redirect()->route('guru.questions.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Question $question)
    {
        $this->authorizeOwner($question);
        $subjects = Subject::orderBy('name')->get();
        return view('admin.questions.edit', compact('question', 'subjects'));
    }

    public function update(Request $request, Question $question)
    {
        $this->authorizeOwner($question);
        $data = $this->validateData($request);
        $isPg = $request->type === 'pilihan_ganda';

        $question->update([
            'subject_id'     => $data['subject_id'],
            'grade'          => $request->input('grade') ?: null,
            'type'           => $data['type'],
            'question'       => $data['question'],
            'option_a'       => $isPg ? $data['option_a'] : null,
            'option_b'       => $isPg ? $data['option_b'] : null,
            'option_c'       => $isPg ? $data['option_c'] : null,
            'option_d'       => $isPg ? $data['option_d'] : null,
            'option_e'       => $isPg ? ($data['option_e'] ?? null) : null,
            'correct_option' => $isPg ? $data['correct_option'] : null,
            'answer_key'     => $request->type === 'essay' ? ($data['answer_key'] ?? null) : null,
            'language'       => $request->type === 'coding' ? ($data['language'] ?? null) : null,
            'starter_code'   => $request->type === 'coding' ? ($data['starter_code'] ?? null) : null,
            'score'          => $data['score'],
        ]);

        return redirect()->route('guru.questions.index')->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Question $question)
    {
        $this->authorizeOwner($question);
        $question->delete();
        return redirect()->route('guru.questions.index')->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * Pastikan soal milik guru yang sedang login.
     */
    private function authorizeOwner(Question $question): void
    {
        abort_if($question->created_by !== auth()->id(), 403, 'Anda tidak berhak mengakses soal ini.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'subject_id'     => 'required|exists:subjects,id',
            'grade'          => 'nullable|in:10,11,12',
            'type'           => 'required|in:pilihan_ganda,essay,coding',
            'question'       => 'required|string',
            'score'          => 'required|integer|min:1',
            'option_a'       => 'nullable|required_if:type,pilihan_ganda',
            'option_b'       => 'nullable|required_if:type,pilihan_ganda',
            'option_c'       => 'nullable|required_if:type,pilihan_ganda',
            'option_d'       => 'nullable|required_if:type,pilihan_ganda',
            'option_e'       => 'nullable',
            'correct_option' => 'nullable|required_if:type,pilihan_ganda|in:a,b,c,d,e',
            'answer_key'     => 'nullable|string',
            'language'       => 'nullable|required_if:type,coding|string',
            'starter_code'   => 'nullable|string',
        ], [
            'correct_option.required_if' => 'Pilih kunci jawaban untuk soal pilihan ganda.',
            'option_a.required_if'       => 'Pilihan A wajib diisi.',
            'option_b.required_if'       => 'Pilihan B wajib diisi.',
            'option_c.required_if'       => 'Pilihan C wajib diisi.',
            'option_d.required_if'       => 'Pilihan D wajib diisi.',
        ]);
    }
}