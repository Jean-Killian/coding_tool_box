<?php

namespace App\Http\Controllers\Knowledge;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Services\QuizStudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentQuizController extends Controller
{
    protected QuizStudentService $quizService;

    public function __construct(QuizStudentService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * Affiche la page d'accueil étudiante avec les QCM assignés, complétés et auto-générés.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            return redirect()->route('knowledge.teacher_index');
        }

        return view('pages.knowledge.index', [
            'assignedQuizzes' => $this->quizService->getAssignedQuizzes($user),
            'completedQuizzes' => $this->quizService->getCompletedQuizzes($user),
            'selfQuizzes' => $this->quizService->getSelfGeneratedQuizzes($user),
        ]);
    }

    /**
     * Affiche la page de réponse à un QCM.
     */
    public function answer(Quiz $quiz)
    {
        return view('pages.knowledge.quiz_answer', [
            'quizQuestions' => $quiz->questions,
            'subject' => $quiz->subject,
            'quizInfo' => [
                'created_at' => $quiz->created_at,
                'question_count' => $quiz->question_count,
            ],
            'action' => route('knowledge.quiz.submit', $quiz)
        ]);
    }

    /**
     * Traite la soumission des réponses à un QCM.
     */
    public function submitAnswers(Request $request, Quiz $quiz)
    {
        $user = Auth::user();

        $answers = $request->input('answers', []);
        $score = $this->quizService->calculateScore($quiz, $answers);

        $cohort = $this->quizService->getCohortForQuiz($user, $quiz);
        if (!$cohort) {
            return redirect()->route('knowledge.index')
                ->with('error', 'Impossible de trouver la cohorte liée à ce QCM.');
        }

        if ($this->quizService->hasAlreadyAnswered($user, $quiz)) {
            return redirect()->route('knowledge.index')
                ->with('error', 'Vous avez déjà répondu à ce QCM.');
        }

        $this->quizService->storeStudentResult($user, $quiz, $score, $answers);

        return redirect()->route('knowledge.quiz.result', $quiz->id);
    }

    /**
     * Affiche la page de résultats d'un QCM complété.
     */
    public function result(Quiz $quiz)
    {
        $user = Auth::user();

        $bilan = $this->quizService->getStudentResult($user, $quiz);

        if (!$bilan || !$bilan->answers) {
            return redirect()->route('knowledge.index')
                ->with('error', 'Aucune réponse enregistrée pour ce QCM.');
        }

        return view('pages.knowledge.result', [
            'quiz' => $quiz->questions,
            'answers' => json_decode($bilan->answers, true),
            'score' => $bilan->score,
            'total' => $quiz->question_count,
        ]);
    }
}
