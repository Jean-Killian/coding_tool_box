<?php

namespace App\Http\Controllers\Knowledge;

use App\Models\Quiz;
use App\Services\QuizAssignmentService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class QuizAssignmentController
{
    use AuthorizesRequests;
    protected QuizAssignmentService $assignmentService;

    public function __construct(QuizAssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * Affecte un QCM à une cohorte et publie le QCM.
     * @throws AuthorizationException
     */
    public function assign(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'cohort_id' => 'required|exists:cohorts,id',
        ]);

        $quiz = Quiz::findOrFail($validated['quiz_id']);

        $this->authorize('assign', $quiz);

        $this->assignmentService->assignQuizToCohort(
            $validated['quiz_id'],
            $validated['cohort_id']
        );

        return redirect()
            ->route('knowledge.teacher_index')
            ->with('success', 'QCM affecté à la cohorte avec succès !');
    }
}
