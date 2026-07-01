<?php

namespace App\Imports;

use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public int $created = 0;
    public int $skipped = 0;
    public array $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            $baris = $i + 2; // +1 header, +1 index mulai 0
            $name  = trim((string) ($row['name'] ?? ''));
            $email = trim((string) ($row['email'] ?? ''));

            if ($name === '' || $email === '') {
                $this->skipped++;
                $this->errors[] = "Baris {$baris}: name/email kosong";
                continue;
            }
            if (User::where('email', $email)->exists()) {
                $this->skipped++;
                $this->errors[] = "Baris {$baris}: email {$email} sudah terdaftar";
                continue;
            }

            // Petakan kelas berdasarkan NAMA kelas (mis. "X-A")
            $classId = null;
            $kelas = trim((string) ($row['kelas'] ?? ''));
            if ($kelas !== '') {
                $c = SchoolClass::where('name', $kelas)->first();
                $classId = $c?->id;
                if (!$c) $this->errors[] = "Baris {$baris}: kelas \"{$kelas}\" tidak ditemukan (dikosongkan)";
            }

            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make(trim((string) ($row['password'] ?? '')) ?: 'password'),
                'nis'      => trim((string) ($row['nis'] ?? '')) ?: null,
                'class_id' => $classId,
            ]);
            $user->assignRole('siswa');

            // Mapel peminatan (opsional, pisah dengan koma). Mis: "Informatika X, Biologi X"
            $peminatan = trim((string) ($row['peminatan'] ?? ''));
            if ($peminatan !== '') {
                $names = array_filter(array_map('trim', explode(',', $peminatan)));
                $ids = Subject::whereIn('name', $names)->where('type', 'pilihan')->pluck('id')->all();
                if (!empty($ids)) $user->electiveSubjects()->sync($ids);
            }

            $this->created++;
        }
    }
}