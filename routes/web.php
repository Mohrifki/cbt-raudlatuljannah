<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\PlotSessionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GradingController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === AREA ADMIN ===
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::get('users/photos/import', [\App\Http\Controllers\Admin\UserController::class, 'photoImportForm'])->name('users.photos.import');
        Route::post('users/photos/import', [\App\Http\Controllers\Admin\UserController::class, 'photoImport'])->name('users.photos.import.store');
        Route::get('users/import', [\App\Http\Controllers\Admin\UserController::class, 'importForm'])->name('users.import');
        Route::post('users/import', [\App\Http\Controllers\Admin\UserController::class, 'import'])->name('users.import.store');
        Route::get('users/import/template', [\App\Http\Controllers\Admin\UserController::class, 'importTemplate'])->name('users.import.template');
        Route::get('grading', [GradingController::class, 'index'])->name('grading.index');
        Route::get('grading/{attempt}', [GradingController::class, 'show'])->name('grading.show');
        Route::put('grading/{attempt}', [GradingController::class, 'update'])->name('grading.update');
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/{exam}/print', [AttendanceController::class, 'print'])->name('attendance.print');
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/{exam}/print', [ReportController::class, 'print'])->name('reports.print');
        Route::get('reports/{exam}/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        // ===== JADWAL PLOT (route baru) =====
        Route::get('plot-sessions',                  [PlotSessionController::class, 'index'])->name('plot-sessions.index');
        Route::post('plot-sessions',                 [PlotSessionController::class, 'store'])->name('plot-sessions.store');
        Route::put('plot-sessions/{plotSession}',    [PlotSessionController::class, 'update'])->name('plot-sessions.update');
        Route::delete('plot-sessions/{plotSession}', [PlotSessionController::class, 'destroy'])->name('plot-sessions.destroy');

        // ===== ATUR PLOT PEMINATAN SISWA (route baru) =====
        // ⚠️ WAJIB di ATAS baris Route::resource('users', ...)
        Route::get('users/{user}/plot', [\App\Http\Controllers\Admin\UserController::class, 'plotForm'])->name('users.plot');
        Route::put('users/{user}/plot', [\App\Http\Controllers\Admin\UserController::class, 'plotStore'])->name('users.plot.store');

        Route::resource('users', UserController::class)->except('show');
        Route::resource('subjects', SubjectController::class)->except('show');
        Route::get('classes/{class}/students', [\App\Http\Controllers\Admin\SchoolClassController::class, 'students'])->name('classes.students');
        Route::post('classes/{class}/students', [\App\Http\Controllers\Admin\SchoolClassController::class, 'syncStudents'])->name('classes.students.sync');
        Route::resource('classes', SchoolClassController::class)->except('show');
        Route::resource('exams', \App\Http\Controllers\Admin\ExamController::class)->except('show');
        Route::get('exams/{exam}/questions', [\App\Http\Controllers\Admin\ExamController::class, 'questions'])->name('exams.questions');
        Route::post('exams/{exam}/questions', [\App\Http\Controllers\Admin\ExamController::class, 'syncQuestions'])->name('exams.questions.sync');
        Route::get('questions/import', [\App\Http\Controllers\Admin\QuestionController::class, 'importForm'])->name('questions.import.form');
        Route::get('questions/import/template', [\App\Http\Controllers\Admin\QuestionController::class, 'downloadTemplate'])->name('questions.import.template');
        Route::post('questions/import', [\App\Http\Controllers\Admin\QuestionController::class, 'import'])->name('questions.import');
        Route::resource('questions', \App\Http\Controllers\Admin\QuestionController::class)->except('show');
        Route::post('media/upload', [\App\Http\Controllers\Admin\MediaUploadController::class, 'store'])->name('media.upload');
    });

    // === AREA GURU ===
    Route::middleware('role:guru')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'guru'])->name('dashboard');

        // ===== BANK SOAL (guru) — hanya soal milik guru sendiri =====
        Route::resource('questions', \App\Http\Controllers\Guru\QuestionController::class)->except('show');
        // Upload media (gambar/audio) untuk editor soal guru
        Route::post('media/upload', [\App\Http\Controllers\Admin\MediaUploadController::class, 'store'])->name('media.upload');

        // ===== MANAJEMEN UJIAN (guru) — hanya ujian milik guru sendiri =====
        Route::get('exams/{exam}/questions', [\App\Http\Controllers\Guru\ExamController::class, 'questions'])->name('exams.questions');
        Route::post('exams/{exam}/questions', [\App\Http\Controllers\Guru\ExamController::class, 'syncQuestions'])->name('exams.questions.sync');
        Route::resource('exams', \App\Http\Controllers\Guru\ExamController::class)->except('show');

        // ===== PENILAIAN (guru) — hanya pengerjaan pada ujian milik guru sendiri =====
        Route::get('grading', [\App\Http\Controllers\Guru\GradingController::class, 'index'])->name('grading.index');
        Route::get('grading/{attempt}', [\App\Http\Controllers\Guru\GradingController::class, 'show'])->name('grading.show');
        Route::put('grading/{attempt}', [\App\Http\Controllers\Guru\GradingController::class, 'update'])->name('grading.update');
    });

    // === AREA SISWA ===
    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'siswa'])->name('dashboard');
    });
    Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('ujian',                    [\App\Http\Controllers\Siswa\ExamController::class, 'index'])->name('exams.index');
        Route::get('ujian/{exam}/mulai',       [\App\Http\Controllers\Siswa\ExamController::class, 'start'])->name('exams.start');
        Route::get('ujian/{exam}/kerjakan',    [\App\Http\Controllers\Siswa\ExamController::class, 'work'])->name('exams.work');
        Route::post('ujian/{exam}/jawab',      [\App\Http\Controllers\Siswa\ExamController::class, 'saveAnswer'])->name('exams.answer');
        Route::post('ujian/{exam}/kumpulkan',  [\App\Http\Controllers\Siswa\ExamController::class, 'submit'])->name('exams.submit');
        Route::post('ujian/{exam}/pelanggaran', [\App\Http\Controllers\Siswa\ExamController::class, 'violation'])->name('exams.violation');
        Route::get('ujian/{exam}/hasil',       [\App\Http\Controllers\Siswa\ExamController::class, 'result'])->name('exams.result');
        // (Fase 5C-2 nanti: mulai, kerjakan, simpan jawaban, submit)
    });
});

require __DIR__ . '/auth.php';
