<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetSiswaPassword extends Command
{
    /**
     * php artisan siswa:reset-password         (dengan konfirmasi)
     * php artisan siswa:reset-password --force  (langsung jalan)
     */
    protected $signature = 'siswa:reset-password {--force : Jalankan tanpa konfirmasi}';

    protected $description = 'Reset password semua siswa menjadi NIS masing-masing';

    public function handle()
    {
        $siswa = User::role('siswa')->whereNotNull('nis')->where('nis', '!=', '')->get();

        if ($siswa->isEmpty()) {
            $this->warn('Tidak ada siswa dengan NIS. Tidak ada yang direset.');
            return self::SUCCESS;
        }

        if (!$this->option('force') &&
            !$this->confirm("Reset password {$siswa->count()} siswa menjadi NIS masing-masing?")) {
            $this->info('Dibatalkan.');
            return self::SUCCESS;
        }

        $count = 0;
        foreach ($siswa as $u) {
            $u->password = Hash::make($u->nis);
            $u->saveQuietly();
            $count++;
        }

        $this->info("Selesai: {$count} password siswa direset menjadi NIS.");
        return self::SUCCESS;
    }
}
