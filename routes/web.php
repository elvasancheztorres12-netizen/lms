<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])
        ->name('dashboard.teacher');

});

// Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.admin');
Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard.teacher');
// Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('dashboard.student');

// TEACHER
Route::prefix('teacher')->middleware('auth')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard.teacher');

    Route::get('/courses', function () {
        return 'Cursos del docente';
    })->name('teacher.courses');

    Route::get('/students', function () {
        return 'Estudiantes del docente';
    })->name('teacher.students');
});