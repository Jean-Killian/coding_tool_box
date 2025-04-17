<?php

namespace App\Http\Controllers\Knowledge;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\QuizCreatorService;

class QuizCreationController extends Controller
{
    use AuthorizesRequests;

    protected QuizCreatorService $quizCreator;

    public function __construct(QuizCreatorService $quizCreator)
    {
        $this->quizCreator = $quizCreator;
    }

    /**
     * Store a newly generated quiz in the database.
     *
     * Validates the user request, decodes the QCM structure,
     * and saves it into the quizzes table.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveGeneratedQuiz(Request $request)
    {
        $this->authorize('create', Quiz::class);

        $data = $this->validateQuizSubmission($request);
        $qcmArray = $this->parseQuizJson($request->qcm);

        if (!$qcmArray) {
            return back()->withErrors(['qcm' => 'Le contenu du QCM est invalide (format JSON incorrect).']);
        }

        $this->quizCreator->create($data, $qcmArray);

        return redirect()->route('knowledge.index')->with('success', 'QCM enregistré');
    }

    private function validateQuizSubmission(Request $request)
    {
        return $request->validate([
            'subject' => 'required|string|max:100',
            'question_count' => 'required|integer|min:1|max:10',
            'publish' => 'nullable|boolean'
        ]);
    }

    private function parseQuizJson($json)
    {
        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }

    /**
     * Displays the form to create a new QCM.
     * Used to define subject and number of questions.
     */
    public function showQuizCreationForm()
    {
        return view('pages.knowledge.generate');
    }
}
