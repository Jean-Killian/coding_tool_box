<?php

namespace App\Http\Controllers;

use App\Services\OlympicCoderService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    protected $olympicCoderService;

    public function __construct(OlympicCoderService $olympicCoderService)
    {
        $this->olympicCoderService = $olympicCoderService;
    }

    public function create(Request $request)
    {
        // Récupère les langues et le nombre de questions du formulaire
        $languages = $request->input('languages');
        $questionCount = $request->input('question_count');

        // Appelle l'API pour générer le QCM
        $quiz = $this->olympicCoderService->generateQuiz($languages, $questionCount);

        // Retourne la vue avec le quiz généré
        return view('quiz.create', ['quiz' => $quiz]);
    }
}

