<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            if (!Schema::hasColumn('exam_attempts', 'started_at'))      $table->timestamp('started_at')->nullable();
            if (!Schema::hasColumn('exam_attempts', 'finished_at'))     $table->timestamp('finished_at')->nullable();
            if (!Schema::hasColumn('exam_attempts', 'status'))          $table->string('status')->default('ongoing');
            if (!Schema::hasColumn('exam_attempts', 'score'))           $table->decimal('score', 8, 2)->nullable();
            if (!Schema::hasColumn('exam_attempts', 'violation_count')) $table->integer('violation_count')->default(0);
            if (!Schema::hasColumn('exam_attempts', 'question_order'))  $table->json('question_order')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            foreach (['score', 'violation_count', 'question_order'] as $col) {
                if (Schema::hasColumn('exam_attempts', $col)) $table->dropColumn($col);
            }
        });
    }
};
