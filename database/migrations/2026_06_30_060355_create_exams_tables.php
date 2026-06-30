<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->enum('type', ['wajib', 'pilihan'])->default('wajib');
            $table->unsignedInteger('duration')->default(60); // menit
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_options')->default(false);
            $table->unsignedInteger('question_count')->nullable(); // untuk mode acak (5B)
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Target kelas (khusus ujian wajib)
        Schema::create('exam_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->unique(['exam_id', 'class_id']);
        });

        // Soal yang masuk paket (diisi di Fase 5B)
        Schema::create('exam_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order')->nullable();
            $table->unique(['exam_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_question');
        Schema::dropIfExists('exam_class');
        Schema::dropIfExists('exams');
    }
};