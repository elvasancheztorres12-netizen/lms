<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'reference_price' => $request->reference_price
        ]);

        return redirect()->route('admin.courses.index');
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'reference_price' => $request->reference_price
        ]);

        return redirect()->route('admin.courses.index');
    }

    public function destroy($id)
    {
        Course::destroy($id);

        return redirect()->route('admin.courses.index');
    }
}