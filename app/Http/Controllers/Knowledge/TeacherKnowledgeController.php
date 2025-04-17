<?php

namespace App\Http\Controllers\Knowledge;

use App\Models\Cohort;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherKnowledgeController
{
    use AuthorizesRequests;
    public function indexTeacher()
    {
        $this->authorize('viewTeacherContent', Quiz::class);

        $publishedQuizzes = Quiz::where('user_id', auth()->id())->where('is_published', true)->get();
        $draftQuizzes = Quiz::where('user_id', auth()->id())->where('is_published', false)->get();

        return view('pages.knowledge.teacher_index', compact('publishedQuizzes', 'draftQuizzes'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'cohort_id' => 'required|exists:cohorts,id',
        ]);

        $quiz = Quiz::find($request->quiz_id);
        $cohort = Cohort::find($request->cohort_id);

        foreach ($cohort->users as $student) {
            DB::table('cohorts_bilans')->updateOrInsert([
                'user_id' => $student->id,
                'quiz_id' => $quiz->id,
                'cohort_id' => $cohort->id,
            ], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $quiz->update(['is_published' => true]);

        return redirect()->route('knowledge.teacher_index')->with('success', 'QCM affecté à la cohorte avec succès !');
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
    /*public function previewGeneratedQuiz(Request $request)
    {
        $this->authorize('viewTeacherContent', Quiz::class);

        $qcmJson = session('qcm');
        $subject = session('subject');
        $questionCount = session('question_count');

        $qcm = json_decode($qcmJson, true);

        return view('pages.knowledge.preview', [
            'qcm' => $qcm,
            'subject' => $subject,
            'questionCount' => $questionCount,
            'qcmRaw' => $qcmJson
        ]);
    }*/

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

}
