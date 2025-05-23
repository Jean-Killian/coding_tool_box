<?php

namespace App\Http\Controllers\Knowledge;

use App\Models\Cohort;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Quiz;
use Illuminate\Http\Request;

class TeacherQuizController
{
    use AuthorizesRequests;

    /**
     * Display all quizzes created by the teacher, grouped by their publication status.
     */
    public function indexTeacher()
    {
        $this->authorize('viewTeacherContent', Quiz::class);

        $quizzes = Quiz::byTeacher(auth()->id())->get()->groupBy('is_published');

        return view('pages.knowledge.teacher_index', [
            'publishedQuizzes' => $quizzes[true] ?? collect(),
            'draftQuizzes' => $quizzes[false] ?? collect(),
        ]);
    }

    /**
     * Display the form to assign a quiz to a cohort.
     */
    public function createAssignment(Request $request)
    {
        $this->authorize('viewTeacherContent', Quiz::class);

        $quizzes = Quiz::byTeacher(auth()->id())->get();
        $cohorts = Cohort::with('users')->get();
        $selectedQuizId = $request->quiz_id;

        return view('pages.knowledge.assign_quiz', compact('quizzes', 'cohorts', 'selectedQuizId'));
    }

    /**
     * Show a preview of the quiz generated by the teacher before saving it.
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

    /**
     * Display a specific quiz in read-only mode with available cohorts.
     */
    public function show(Quiz $quiz)
    {
        $this->authorize('viewTeacherContent', Quiz::class);

        $cohorts = Cohort::all();
        return view('pages.knowledge.quiz_display', [
            'mode' => 'show',
            'quiz' => $quiz,
            'cohorts' => $cohorts
        ]);
    }

    /**
     * Delete a specific quiz created by the teacher.
     */
    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);

        $quiz->delete();

        return redirect()->route('knowledge.teacher_index')->with('success', 'QCM supprimé avec succès.');
    }
}
