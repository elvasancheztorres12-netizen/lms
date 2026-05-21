<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Task; 
use App\Models\TaskSubmission;

class TaskController extends Controller
{
    /**
     * Almacena una nueva tarea asignada desde el modal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'training_id'   => 'required|exists:trainings,training_id',
            'title'         => 'required|string|max:150',
            'description'   => 'nullable|string',
            'delivery_date' => 'required|date', 
        ]);

        $user = auth()->user();

        $training = Training::where('training_id', $request->training_id)
            ->where('teacher_id', $user->user_id)
            ->firstOrFail();

        Task::create([
            'training_id' => $training->training_id,
            'title'       => $request->title,
            'description' => $request->description ?? null,
            'due_date'    => $request->delivery_date, // Guardamos el valor en el campo de la BD
        ]);

        return redirect()->route('teacher.courses.show', ['id' => $training->training_id, 'tab' => 'contenido'])
            ->with('success', 'Tarea asignada correctamente.');
    }

    /**
     * Muestra la lista de alumnos y sus entregas para una tarea específica.
     */
    public function submissions($task_id)
    {
        $user = auth()->user();

        $task = Task::where('task_id', $task_id)
            ->whereHas('training', function ($query) use ($user) {
                $query->where('teacher_id', $user->user_id);
            })->firstOrFail();

        $submissions = $task->submissions()
            ->with(['student.person']) 
            ->get();

        return view('teacher.tasks.submissions', compact('task', 'submissions'));
    }

    /**
     * Procesa y almacena la calificación y feedback de una entrega.
     */
    public function grade(Request $request, $submission_id)
    {
        $request->validate([
            'grade'    => 'required|numeric|min:0|max:20',
            'feedback' => 'nullable|string',
        ]);

        $user = auth()->user();

        $submission = TaskSubmission::where('submission_id', $submission_id)
            ->whereHas('task.training', function ($query) use ($user) {
                $query->where('teacher_id', $user->user_id);
            })->firstOrFail();

        $submission->update([
            'grade'            => $request->grade,
            'teacher_feedback' => $request->feedback,
            'graded_at'        => now(),
        ]);

        return redirect()->route('teacher.tasks.submissions', ['task_id' => $submission->task_id])
            ->with('success', 'Entrega calificada correctamente.');
    }
}