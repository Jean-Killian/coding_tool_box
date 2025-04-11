<?php

namespace App\Http\Controllers\Knowledge;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherQuizController extends Controller
{
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
        $request->validate([
            'subject' => 'required|string|max:100',
            'question_count' => 'required|integer|min:1|max:20',
            'publish' => 'required|boolean'
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
            'is_published' => $request->publish
        ]);

        return redirect()->route('knowledge.list')->with('success', 'QCM enregistré');
    }


    /**
     * Displays all QCMs created by the current user.
     * Retrieves and orders quizzes by creation date.
     */
    public function list()
    {
        $quizzes = Quiz::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('pages.knowledge.list', compact('quizzes'));
    }


    /**
     * Displays the form to create a new QCM.
     * Used to define subject and number of questions.
     */
    public function createForm()
    {
        return view('pages.knowledge.generate');
    }


    /**
     * Displays a specific saved QCM in read-only mode.
     * Loads a quiz by ID and shows its details.
     */
    public function show(Quiz $quiz)
    {
        return view('pages.knowledge.show', [
            'quiz' => $quiz
        ]);
    }
}
