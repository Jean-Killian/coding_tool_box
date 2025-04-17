<?php

namespace App\Http\Controllers\Knowledge;

use App\Models\Cohort;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Quiz;
use Illuminate\Http\Request;

class TeacherQuizController
{
    use AuthorizesRequests;
    public function indexTeacher()
    {
        $this->authorize('viewTeacherContent', Quiz::class);

        $publishedQuizzes = Quiz::where('user_id', auth()->id())->where('is_published', true)->get();
        $draftQuizzes = Quiz::where('user_id', auth()->id())->where('is_published', false)->get();

        return view('pages.knowledge.teacher_index', compact('publishedQuizzes', 'draftQuizzes'));
    }

    public function showAssignForm(Request $request)
    {
        $quizzes = Quiz::all();
        $cohorts = Cohort::all();
        $selectedQuizId = $request->quiz_id;

        return view('pages.knowledge.assign_quiz', compact('quizzes', 'cohorts', 'selectedQuizId'));
    }

    /**
     * Shows a preview of the generated QCM before saving.
     * Retrieves data from session and sends it to the preview view.
     */
    public function previewGeneratedQuiz()
    {
        $this->authorize('viewTeacherContent', Quiz::class);

        $qcmJson = session('qcm');
        $subject = session('subject');
        $questionCount = session('question_count');

        $qcm = json_decode($qcmJson, true);

        return view('pages.knowledge.quiz_display', [
            'mode' => 'preview',
            'quiz' => $qcm,
            'subject' => $subject,
            'questionCount' => $questionCount
        ]);
    }

    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);

        $quiz->delete();

        return redirect()->route('knowledge.teacher_index')->with('success', 'QCM supprimé avec succès.');
    }

    /**
     * Displays a specific saved QCM in read-only mode.
     * Loads a quiz by ID and shows its details.
     */
    public function show(Quiz $quiz)
    {
        $cohorts = Cohort::all();
        return view('pages.knowledge.quiz_display', [
            'mode' => 'show',
            'quiz' => $quiz,
            'cohorts' => $cohorts
        ]);
    }
}
