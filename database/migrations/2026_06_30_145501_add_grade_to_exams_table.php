<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'grade')) {
                $table->string('grade', 5)->nullable()->after('type'); // 10/11/12 atau null = semua
            }
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams', 'grade')) {
                $table->dropColumn('grade');
            }
        });
    }
};