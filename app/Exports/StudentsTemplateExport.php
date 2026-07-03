<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StudentsTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
  public function headings(): array
  {
    return ['name', 'email', 'password', 'nis', 'kelas', 'plot1', 'plot2', 'plot3', 'plot4'];
  }

  public function array(): array
  {
    return [
      ['Budi Santoso', 'budi@sekolah.sch.id', 'password', '2024001', 'X-A',  '', '', '', ''],
      ['Rina Melati',  'rina@sekolah.sch.id', '',         '2023005', 'XI-A', 'Informatika', 'Matematika Peminatan', 'Fisika', 'Ekonomi'],
    ];
  }

  public function styles(Worksheet $sheet)
  {
    $sheet->getStyle('A1:I1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
    $sheet->getStyle('A1:I1')->getFill()
      ->setFillType(Fill::FILL_SOLID)
      ->getStartColor()->setRGB('16A34A'); // hijau
    return [];
  }
}
