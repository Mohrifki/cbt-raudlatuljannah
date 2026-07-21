<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Jadikan kolom grade boleh kosong (null = Semua Tingkat).
        // Pakai raw SQL agar tidak butuh doctrine/dbal untuk ->change().
        if (Schema::hasColumn('questions', 'grade')) {
            DB::statement("ALTER TABLE `questions` MODIFY `grade` VARCHAR(5) NULL DEFAULT NULL");
        }
    }

    public function down(): void
    {
        // Dibiarkan tetap nullable; tidak dikembalikan ke NOT NULL agar aman.
    }
};
