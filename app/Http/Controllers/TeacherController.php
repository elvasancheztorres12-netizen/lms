<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $trainings = Training::with(['course', 'enrollments'])
            ->where('teacher_id', $user->user_id)
            ->get();

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

    public function courses()
    {
        $user = auth()->user();

        $trainings = Training::with(['course', 'enrollments'])
            ->where('teacher_id', $user->user_id)
            ->get();

        return view('teacher.courses.index', compact('trainings'));
    }

    public function students($id)
    {
        $training = Training::with('course')->findOrFail($id);

        $students = \App\Models\Enrollment::with('student.person')
            ->where('training_id', $id)
            ->get();

        return view('teacher.courses.students', compact('training', 'students'));
    }
}