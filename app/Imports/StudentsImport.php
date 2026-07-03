<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
  public int $created = 0;      // 👈 dipakai import()
  public int $skipped = 0;      // 👈 dipakai import()
  public array $errors = [];    // 👈 dipakai import()

  public function collection(Collection $rows)
  {
    foreach ($rows as $i => $row) {
      $baris = $i + 2; // +2: baris header + index mulai 0

      // lewati baris kosong
      if (blank($row['name'] ?? null) || blank($row['email'] ?? null)) {
        $this->skipped++;
        continue;
      }

      // cari kelas
      $class = SchoolClass::where('name', trim($row['kelas'] ?? ''))->first();
      if (!$class) {
        $this->skipped++;
        $this->errors[] = "Baris {$baris}: kelas '" . ($row['kelas'] ?? '-') . "' tidak ditemukan";
        continue;
      }

      // buat / update siswa (password lama tidak tertimpa)
      $user  = User::firstOrNew(['email' => trim($row['email'])]);
      $isNew = !$user->exists;

      $user->name     = trim($row['name']);
      $user->nis      = $row['nis'] ?? null;
      $user->class_id = $class->id;
      if ($isNew || filled($row['password'] ?? null)) {
        $user->password = Hash::make(($row['password'] ?? '') ?: 'password');
      }
      $user->save();

      if (!$user->hasRole('siswa')) {
        $user->assignRole('siswa');
      }

      // ===== PEMINATAN (Plot 1–4) — hanya kelas XI & XII =====
      if (in_array($class->grade, ['XI', 'XII'], true)) {
        $sync = [];
        foreach ([1, 2, 3, 4] as $p) {
          $namaMapel = trim($row['plot' . $p] ?? '');
          if ($namaMapel === '') continue;

          $subject = Subject::where('type', 'pilihan')
            ->where('name', $namaMapel)->first();

          if ($subject) {
            $sync[$subject->id] = ['plot' => $p];
          } else {
            $this->errors[] = "Baris {$baris}: mapel '{$namaMapel}' (plot{$p}) tidak ditemukan";
          }
        }
        if (!empty($sync)) {
          $user->electiveSubjects()->sync($sync);
        }
      }

      if ($isNew) {
        $this->created++;
      }
    }
  }
}
