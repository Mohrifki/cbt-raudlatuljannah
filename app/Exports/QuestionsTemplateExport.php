<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuestionsTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['Kode Mapel', 'Tipe', 'Pertanyaan', 'Opsi A', 'Opsi B', 'Opsi C', 'Opsi D', 'Opsi E', 'Kunci', 'Kunci Jawaban', 'Bahasa', 'Kode Awal', 'Skor'];
    }

    public function array(): array
    {
        return [
            ['MTK', 'pg', '2 + 3 = ?', '4', '5', '6', '7', '', 'b', '', '', '', 1],
            ['MTK', 'essay', 'Jelaskan teorema Pythagoras.', '', '', '', '', '', '', 'a^2 + b^2 = c^2', '', '', 5],
            ['INF', 'coding', 'Buat fungsi menjumlahkan dua angka.', '', '', '', '', '', '', '', 'python', "def tambah(a, b):\n    return a + b", 10],
        ];
    }
}