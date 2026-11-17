<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
 
    // Lihat data (admin dan teacher)
    Route::middleware('role:admin,teacher')->group(function () {
        Route::get('students', [StudentController::class, 'index'])->name('students.index');
        Route::get('teachers', [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('classes', [ClassController::class, 'index'])->name('classes.index');
        Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
    });

    // Tambah, ubah, hapus (admin saja). Data Pengguna seluruhnya admin saja.
    Route::middleware('role:admin')->group(function () {
        Route::resource('students', StudentController::class)->except(['index', 'show']);
        Route::resource('teachers', TeacherController::class)->except(['index', 'show']);
        Route::resource('classes', ClassController::class)->except(['index', 'show']);
        Route::resource('subjects', SubjectController::class)->except(['index', 'show']);
        Route::resource('users', UserController::class)->except(['show']);
    });
    });

require __DIR__.'/auth.php';