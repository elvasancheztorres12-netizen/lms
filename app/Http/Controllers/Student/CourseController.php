<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Enrollment;
use App\Models\Training;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CourseController extends Controller
{
    public function show($id)
    {
        $studentId = auth()->id();

        $isEnrolled = Enrollment::where('student_id', $studentId)
            ->where('training_id', $id)
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'No estás inscrito en esta capacitación.');
        }

        $training = Training::with(['course', 'teacher.person', 'assessments', 'tasks.submissions'])
            ->where('training_id', $id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('training_id', $id)
            ->firstOrFail();

        $attempts = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
            ->with('assessment')
            ->orderByDesc('created_at')
            ->get();

        return view('student.courses.show', compact('training', 'attempts'));
    }

    public function submitTask(Request $request, $taskId)
    {
        $request->validate([
            'task_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,png,jpg,odt,ods|max:12288',
        ]);

        if ($request->hasFile('task_file')) {
            $file = $request->file('task_file');
            
            $fileName = 'submission_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('submissions', $fileName, 'public');

            TaskSubmission::create([
                'task_id'      => $taskId,
                'student_id'   => auth()->id(),
                'file_path'    => $filePath, 
                'submitted_at' => Carbon::now(),
            ]);

            return back()->with('success', '¡Tu archivo ha sido subido e ingresado con éxito!');
        }

        return back()->withErrors(['task_file' => 'No se pudo procesar el archivo seleccionado.']);
    }

    public function takeExam($assessment_id)
    {
        $studentId = auth()->id();

        $assessment = Assessment::with('questions.alternatives')
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('training_id', $assessment->training_id)
            ->firstOrFail();

        $this->validateAssessmentAvailability($assessment);

        $pendingAttempt = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
            ->where('assessment_id', $assessment_id)
            ->whereColumn('created_at', 'updated_at')
            ->latest('attempt_id')
            ->first();

        if ($pendingAttempt) {
            $attempt = $pendingAttempt;
        } else {
            $this->ensureAttemptAllowed($assessment, $enrollment);

            $attemptNumber = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
                ->where('assessment_id', $assessment_id)
                ->count() + 1;

            $attempt = AssessmentAttempt::create([
                'enrollment_id' => $enrollment->enrollment_id,
                'assessment_id' => $assessment_id,
                'number' => $attemptNumber,
                'date' => Carbon::now()->toDateString(),
                'score' => 0,
            ]);
        }

        $elapsedMinutes = Carbon::now()->diffInMinutes($attempt->created_at);
        $timeLimit = max(1, $assessment->time_limit - $elapsedMinutes);

        return view('student.courses.take', compact('assessment', 'timeLimit', 'enrollment', 'attempt'));
    }

    public function submitExam(Request $request, $assessment_id)
    {
        $studentId = auth()->id();

        $assessment = Assessment::with('questions.alternatives')
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('training_id', $assessment->training_id)
            ->firstOrFail();

        $this->validateAssessmentAvailability($assessment);

        $validated = $request->validate([
            'attempt_id' => 'required|integer|exists:assessment_attempts,attempt_id',
            'answers' => 'required|array',
            'answers.*' => 'nullable|integer|exists:alternatives,option_id',
        ]);

        $attempt = AssessmentAttempt::where('attempt_id', $validated['attempt_id'])
            ->where('enrollment_id', $enrollment->enrollment_id)
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

        if ($attempt->created_at->ne($attempt->updated_at)) {
            abort(403, 'Este intento ya fue enviado.');
        }

        $this->ensureAttemptAllowed($assessment, $enrollment, $attempt);

        $timeLimit = $assessment->time_limit;
        $elapsedSeconds = Carbon::now()->diffInSeconds($attempt->created_at);
        $maxSeconds = ($timeLimit * 60) + 120;

        // CORRECCIÓN CRÍTICA: Se añade el return explícito para que no calcule puntajes abajo si expiró el tiempo
        if ($elapsedSeconds > $maxSeconds) {
            $attempt->score = 0;
            $attempt->touch(); // Fuerza el cambio de updated_at para cerrar el intento
            $attempt->save();
            $attempt->load('assessment');

            return view('student.assessments.result', compact('attempt'));
        }

        $totalScore = 0;
        $responses = $request->input('answers', []);

        foreach ($assessment->questions as $question) {
            $selectedOptionId = $responses[$question->question_id] ?? null;

            if ($selectedOptionId) {
                $selectedOption = $question->alternatives->firstWhere('option_id', $selectedOptionId);

                if ($selectedOption && $selectedOption->is_correct) {
                    $totalScore += $question->score;
                }
            }
        }

        $attempt->score = $totalScore;
        $attempt->touch(); // Marcamos modificación para romper la igualdad created_at == updated_at
        $attempt->save();
        $attempt->load('assessment');

        return view('student.assessments.result', compact('attempt'));
    }

    // CORRECCIÓN: Parseo seguro usando Carbon de forma directa y limpia
    private function validateAssessmentAvailability(Assessment $assessment)
    {
        if (! $assessment->active) {
            abort(403, 'Esta evaluación no está disponible.');
        }

        $now = Carbon::now();
        $startDate = Carbon::parse($assessment->start_date)->startOfDay();
        $endDate = Carbon::parse($assessment->end_date)->endOfDay();

        if ($now->lt($startDate) || $now->gt($endDate)) {
            abort(403, 'Esta evaluación está fuera de las fechas permitidas.');
        }
    }

    private function ensureAttemptAllowed(Assessment $assessment, Enrollment $enrollment, AssessmentAttempt $currentAttempt = null)
    {
        $attemptQuery = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
            ->where('assessment_id', $assessment->assessment_id);

        if ($currentAttempt) {
            $attemptQuery->where('attempt_id', '!=', $currentAttempt->attempt_id);
        }

        $previousAttempts = $attemptQuery->count();

        if ($previousAttempts >= $assessment->allowed_attempts) {
            abort(403, 'Ha alcanzado el número máximo de intentos permitidos.');
        }
    }
}