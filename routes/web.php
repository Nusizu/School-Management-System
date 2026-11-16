<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
 
    // Students: lihat data (admin dan teacher)
    Route::middleware('role:admin,teacher')->group(function () {
        Route::get('students', [StudentController::class, 'index'])->name('students.index');
    });

    // Students: tambah, ubah, hapus (admin saja)
    Route::middleware('role:admin')->group(function () {
        Route::resource('students', StudentController::class)->except(['index', 'show']);
    });
    });

require __DIR__.'/auth.php';