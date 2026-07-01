<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['name', 'email', 'password', 'nis', 'kelas', 'peminatan'];
    }

    public function array(): array
    {
        return [
            ['Budi Santoso', 'budi@sekolah.sch.id', 'password', '2024001', 'X-A', 'Informatika X'],
            ['Siti Aminah',  'siti@sekolah.sch.id', '',         '2024002', 'X-A', ''],
        ];
    }
}