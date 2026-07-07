<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExamRecapExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
  public function __construct(protected \Illuminate\Support\Collection $rows, protected string $sheetTitle = 'Rekap Nilai') {}

  public function array(): array
  {
    $data = [];
    foreach ($this->rows as $i => $r) {
      $data[] = [
        $i + 1,
        $r->nis ?? '-',
        $r->name,
        $r->kelas ?? '-',
        $r->status_label,
        $r->score ?? '-',
        $r->violations ?? 0,
      ];
    }
    return $data;
  }

  public function headings(): array
  {
    return ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Status', 'Nilai', 'Pelanggaran'];
  }

  public function title(): string
  {
    return $this->sheetTitle;
  }

  public function styles(Worksheet $sheet)
  {
    $sheet->getStyle('A1:G1')->getFont()->setBold(true);
    return [];
  }
}
