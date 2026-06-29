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
        Route::resource('users', UserController::class)->except('show');
        Route::resource('subjects', SubjectController::class)->except('show');
        Route::resource('classes', SchoolClassController::class)->except('show');
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
