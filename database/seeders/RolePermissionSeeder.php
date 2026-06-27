<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat 3 role
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $guru  = Role::firstOrCreate(['name' => 'guru']);
        $siswa = Role::firstOrCreate(['name' => 'siswa']);

        // 2. Buat user Admin
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@cbt.test'],                              // pencarian berdasarkan email
            ['name' => 'Administrator', 'password' => Hash::make('password')]  // data kalau belum ada
        );
        $userAdmin->assignRole('admin');

        // 3. Buat user Guru contoh
        $userGuru = User::firstOrCreate(
            ['email' => 'guru@cbt.test'],                              // kunci pencarian
            ['name' => 'Guru Contoh', 'password' => Hash::make('password')]
        );
        $userGuru->assignRole('guru');

        // 4. Buat user Siswa contoh
        $userSiswa = User::firstOrCreate(
            ['email' => 'siswa@cbt.test'],                             // kunci pencarian
            ['name' => 'Siswa Contoh', 'password' => Hash::make('password')]
        );
        $userSiswa->assignRole('siswa');
    }
}
