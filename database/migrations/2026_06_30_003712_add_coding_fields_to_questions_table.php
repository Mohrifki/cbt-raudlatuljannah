<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan 'coding' ke enum type
        DB::statement("ALTER TABLE questions MODIFY COLUMN type ENUM('pilihan_ganda','essay','coding') NOT NULL DEFAULT 'essay'");

        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'language')) {
                $table->string('language')->nullable()->after('answer_key');
            }
            if (!Schema::hasColumn('questions', 'starter_code')) {
                $table->longText('starter_code')->nullable()->after('language');
            }
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['language', 'starter_code']);
        });
        DB::statement("ALTER TABLE questions MODIFY COLUMN type ENUM('pilihan_ganda','essay') NOT NULL DEFAULT 'essay'");
    }
};