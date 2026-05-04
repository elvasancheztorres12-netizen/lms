<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $trainings = \App\Models\Training::with(['course', 'enrollments'])
            ->where('teacher_id', $user->user_id)
            ->get();

        // estadísticas
        $totalCourses = $trainings->count();
        $totalStudents = $trainings->sum(function ($t) {
            return $t->enrollments->count();
        });

        return view('teacher.dashboard', compact(
            'trainings',
            'totalCourses',
            'totalStudents'
        ));
    }
}