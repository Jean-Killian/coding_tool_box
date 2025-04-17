<?php

namespace App\Http\Controllers\Knowledge;

use App\Http\Controllers\Controller;
use App\Services\QuizAIService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IAQuizGeneratorController extends Controller
{
    /**
     * Handles the full flow of generating a quiz from user inputs via AI.
     */
    public function generateQuizFromUserInput(Request $request)
    {
        $data = $this->validateInputs($request);

        $prompt = $this->buildPrompt($data['subject'], $data['count'], $data['answers']);

        $qcm = $this->fetchQuizFromAI($prompt);

        if (!is_array($qcm) || empty($qcm)) {
            return redirect()->route('knowledge.generate')
                ->withErrors(['qcm' => 'La génération du bilan a échoué. Essayez un sujet plus précis.']);
        }

        $user = auth()->user();

        if (!$user->isTeacher()) {
            session([
                'temp_quiz' => $qcm,
                'temp_subject' => $data['subject'],
            ]);

            return redirect()->route('knowledge.temp.answer');
        }

        session([
            'qcm' => json_encode($qcm),
            'subject' => $data['subject'],
            'question_count' => $data['count']
        ]);

        return redirect()->route('knowledge.preview');
    }

    /**
     * Validates user inputs from the form and returns them.
     */
    private function validateInputs(Request $request): array
    {
        return $request->validate([
            'subject' => 'required|string|max:100',
            'count' => 'required|integer|min:1|max:10',
            'answers' => 'required|integer|min:2|max:6',
        ]);
    }

    /**
     * Builds the AI prompt string based on subject, count, and answer count.
     */
    private function buildPrompt(string $subject, int $count, int $answers): string
    {
        return <<<EOT
        Tu es un générateur de bilans de compétences pour des étudiants en développement.

        Ta mission est de créer un QCM structuré sur le sujet suivant : **{$subject}**.

        1. Il doit contenir exactement {$count} questions.
        2. Chaque question doit porter sur le sujet **{$subject}**.
        3. Chaque question doit proposer **exactement {$answers} choix de réponse** (PAS PLUS, PAS MOINS).
        4. Le format des réponses doit être un tableau comme celui-ci : "options": ["Réponse A", "Réponse B", ...]
        5. Chaque question doit inclure 4 champs :
            - "question" : l'intitulé de la question
            - "options" : un tableau de {$answers} propositions
            - "answer" : la bonne réponse
            - "difficulty" : facile, moyen ou difficile

        6. La répartition des difficultés doit être :
            - 30% des questions faciles
            - 40% des questions moyennes
            - 30% des questions difficiles

        Tu dois retourner UNIQUEMENT un tableau JSON strictement valide, **sans aucun texte autour**.

        Voici un exemple avec 4 réponses possibles :
        [
            {
                "question": "Que fait la commande `php artisan migrate` en Laravel ?",
                "options": ["Créer une table", "Supprimer une table", "Exécuter les migrations", "Créer un contrôleur"],
                "answer": "Exécuter les migrations",
                "difficulty": "facile"
            }
        ]

        Maintenant, génère le QCM sur le sujet **{$subject}**. Ne mets aucun texte ou explication, retourne uniquement du JSON.
    EOT;
    }

    /**
     * Sends the prompt to the Mistral API and returns the decoded QCM.
     */
    private function fetchQuizFromAI(string $prompt): ?array
    {
        try {
            $mistral = new QuizAIService();
            $response = $mistral->generateQuestionnaire([
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            return json_decode($response['choices'][0]['message']['content'] ?? '', true);

        } catch (Exception $e) {
            Log::error('Erreur IA : ' . $e->getMessage());

            return null;
        }
    }

    public function showTempAnswerForm()
    {
        $quiz = session('temp_quiz');
        $subject = session('temp_subject');

        if (!$quiz) {
            return redirect()->route('knowledge.index')->with('error', 'Aucun QCM temporaire trouvé.');
        }

        return view('pages.knowledge.quiz_answer', [
            'quizQuestions' => $quiz,
            'subject' => $subject,
            'action' => route('knowledge.temp.submit')
        ]);
    }

    public function submitTempAnswers(Request $request)
    {
        $quiz = session('temp_quiz');

        if (!$quiz) {
            return redirect()->route('knowledge.index')->with('error', 'QCM expiré ou invalide.');
        }

        $answers = $request->input('answers', []);
        $score = 0;

        foreach ($quiz as $index => $question) {
            if (isset($answers[$index]) && $answers[$index] === $question['answer']) {
                $score++;
            }
        }

        return view('pages.knowledge.result', [
            'quiz' => $quiz,
            'answers' => $answers,
            'score' => $score,
            'total' => count($quiz),
        ]);
    }
}
