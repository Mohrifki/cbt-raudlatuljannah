<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plot_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('plot');       // 1 - 4
            $table->string('grade')->nullable();       // '10'/'11'/'12', null = semua tingkat
            $table->string('label')->nullable();       // mis. "Ujian Peminatan Plot 1"
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('plot_sessions'); }
};