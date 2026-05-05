<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $enrollments = Enrollment::with([
            'training.course',
            'training.teacher',
            'training.enrollments'
        ])
            ->where('student_id', $user->user_id)
            ->get();

        $totalCourses = $enrollments->count();

        return view('student.dashboard', compact(
            'enrollments',
            'totalCourses'
        ));
    }
}