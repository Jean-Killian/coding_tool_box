<?php

namespace App\Http\Controllers\Knowledge;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KnowledgeController extends Controller
{
    use AuthorizesRequests;

    /**
     * Shows a preview of the generated QCM before saving.
     * Retrieves data from session and sends it to the preview view.
     */
    public function reviewGenerated(Request $request)
    {
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
    }

    /**
     * Saves the validated QCM to the database.
     * Validates the form, decodes JSON, and stores the quiz.
     */
    public function saveGenerated(Request $request)
    {
        if ($request->publish && !auth()->user()->can('create', Quiz::class)) {
            abort(403, 'Vous n\'êtes pas autorisé à publier ce QCM.');
        }

        $request->validate([
            'subject' => 'required|string|max:100',
            'question_count' => 'required|integer|min:1|max:10',
            'publish' => 'nullable|boolean'
        ]);

        $qcmArray = json_decode($request->input('qcm'), true);

        if (!is_array($qcmArray))
        {
            return back()->withErrors([
                'qcm' => 'Le contenu du QCM est invalide (format JSON incorrect).'
            ]);
        }

        Quiz::create
        ([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'question_count' => $request->question_count,
            'questions' => json_decode($request->qcm, true),
            'is_published' => $request->boolean('publish', false),
        ]);

        return redirect()->route('knowledge.index')->with('success', 'QCM enregistré');
    }

    /**
     * Displays all QCMs created by the current user.
     * Retrieves and orders quizzes by creation date.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isTeacher()) {
            return redirect()->route('knowledge.teacher_index');
        }

        $assignedQuizzes = Quiz::whereHas('cohorts', function ($query) use ($user) {
            $query->whereIn('cohorts.id', $user->cohorts->pluck('id'));
        })
            ->whereDoesntHave('students', function ($query) use ($user) {
                $query->where('user_id', $user->id)->whereNotNull('score');
            })
            ->get();

        $completedQuizzes = $user->quizzes()->wherePivotNotNull('score')->get();

        $selfQuizzes = Quiz::where('user_id', $user->id)->get();

        return view('pages.knowledge.index', compact(
            'assignedQuizzes',
            'completedQuizzes',
            'selfQuizzes'
        ));
    }

    /**
     * Displays the form to create a new QCM.
     * Used to define subject and number of questions.
     */
    public function create()
    {
        return view('pages.knowledge.generate');
    }

    /**
     * Displays a specific saved QCM in read-only mode.
     * Loads a quiz by ID and shows its details.
     */
    public function show(Quiz $quiz)
    {
        $cohorts = Cohort::all();

        return view('pages.knowledge.show', [
            'quiz' => $quiz,
            'cohorts' => $cohorts
        ]);
    }



}
