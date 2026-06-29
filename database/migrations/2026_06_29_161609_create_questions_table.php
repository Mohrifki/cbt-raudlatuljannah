<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('questions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $table->enum('type', ['pilihan_ganda', 'essay'])->default('essay');
      $table->text('question');
      $table->text('option_a')->nullable();
      $table->text('option_b')->nullable();
      $table->text('option_c')->nullable();
      $table->text('option_d')->nullable();
      $table->text('option_e')->nullable();
      $table->char('correct_option', 1)->nullable(); // a/b/c/d/e
      $table->text('answer_key')->nullable();         // kunci/rubrik essay
      $table->unsignedInteger('score')->default(1);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('questions');
  }
};
