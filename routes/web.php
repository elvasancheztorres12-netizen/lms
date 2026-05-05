<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| TEACHER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('teacher')->group(function () {

    // Dashboard
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])
        ->name('dashboard.teacher');

    // Cursos del docente
    Route::get('/courses', [TeacherController::class, 'courses'])
        ->name('teacher.courses');

    // Estudiantes de un curso
    Route::get('/courses/{id}/students', [TeacherController::class, 'students'])
        ->name('teacher.courses.students');

});