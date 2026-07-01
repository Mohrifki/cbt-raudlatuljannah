<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\UserController;

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
        Route::get('users/import', [\App\Http\Controllers\Admin\UserController::class, 'importForm'])->name('users.import');
        Route::post('users/import', [\App\Http\Controllers\Admin\UserController::class, 'import'])->name('users.import.store');
        Route::get('users/import/template', [\App\Http\Controllers\Admin\UserController::class, 'importTemplate'])->name('users.import.template');
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
    });

    // === AREA SISWA ===
    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'siswa'])->name('dashboard');
    });
});

require __DIR__ . '/auth.php';
