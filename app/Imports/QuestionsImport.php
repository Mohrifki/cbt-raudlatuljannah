<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Subject;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToCollection, WithHeadingRow
{
    public int $imported = 0;
    public array $errors = [];

    public function collection(Collection $rows)
    {
        $subjects = Subject::all()->keyBy(fn ($s) => strtolower(trim($s->code)));
        $typeMap = ['pg' => 'pilihan_ganda', 'pilihan_ganda' => 'pilihan_ganda', 'essay' => 'essay', 'esai' => 'essay', 'coding' => 'coding'];

        foreach ($rows as $i => $row) {
            $line = $i + 2; // baris 1 = heading
            $question = trim((string) ($row['pertanyaan'] ?? ''));
            if ($question === '') continue; // lewati baris kosong

            $code = strtolower(trim((string) ($row['kode_mapel'] ?? '')));
            $subject = $subjects->get($code);
            if (!$subject) {
                $this->errors[] = "Baris {$line}: kode mapel '{$code}' tidak ditemukan.";
                continue;
            }

            $type = $typeMap[strtolower(trim((string) ($row['tipe'] ?? 'essay')))] ?? 'essay';
            $isPg = $type === 'pilihan_ganda';
            $correct = strtolower(trim((string) ($row['kunci'] ?? '')));

            // Tingkat/kelas: 10/11/12 atau X/XI/XII, kosong = semua tingkat (null)
            $gradeRaw = strtolower(trim((string) ($row['tingkat'] ?? '')));
            $gradeMap = ['x' => '10', 'xi' => '11', 'xii' => '12', '10' => '10', '11' => '11', '12' => '12'];
            $grade = $gradeMap[$gradeRaw] ?? null;

            Question::create([
                'subject_id'     => $subject->id,
                'created_by'     => auth()->id(),
                'grade'          => $grade,
                'type'           => $type,
                'question'       => $question,
                'option_a'       => $isPg ? ($row['opsi_a'] ?? null) : null,
                'option_b'       => $isPg ? ($row['opsi_b'] ?? null) : null,
                'option_c'       => $isPg ? ($row['opsi_c'] ?? null) : null,
                'option_d'       => $isPg ? ($row['opsi_d'] ?? null) : null,
                'option_e'       => $isPg ? ($row['opsi_e'] ?? null) : null,
                'correct_option' => ($isPg && in_array($correct, ['a','b','c','d','e'])) ? $correct : null,
                'answer_key'     => $type === 'essay' ? ($row['kunci_jawaban'] ?? null) : null,
                'language'       => $type === 'coding' ? ($row['bahasa'] ?? null) : null,
                'starter_code'   => $type === 'coding' ? ($row['kode_awal'] ?? null) : null,
                'score'          => (int) ($row['skor'] ?? 1) ?: 1,
            ]);
            $this->imported++;
        }
    }
}