<!-- <?php
//  

// namespace App\Http\Controllers;

// use App\Models\Course;
// use Illuminate\Http\Request;

// class CourseController extends Controller
// {
//     public function index()
//     {
//         $courses = Course::where('teacher_id', auth()->id())->get();
//         return view('dashboard.my-courses', compact('courses'));
//     }

//     public function create()
//     {
//         return view('courses.manage');
//     }

//     public function store(Request $request)
//     {
//         Course::create([
//             'name' => $request->name,
//             'description' => $request->description,
//             'teacher_id' => auth()->id()
//         ]);

//         return redirect()->route('courses.index');
// //     }
// } -->