<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Administrator'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard.admin');

});

/*
|--------------------------------------------------------------------------
| TEACHER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Teacher'])->group(function () {

    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])
        ->name('dashboard.teacher');

    Route::get('/teacher/courses', [TeacherController::class, 'courses'])
        ->name('teacher.courses');

    Route::get('/teacher/courses/{id}/students', [TeacherController::class, 'students'])
        ->name('teacher.courses.students');
});

/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Student'])->group(function () {

    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
        ->name('dashboard.student');

});