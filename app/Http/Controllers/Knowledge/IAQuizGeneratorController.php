<?php

namespace App\Http\Controllers\Knowledge;

use App\Http\Controllers\Controller;
use App\Services\MistralApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IAQuizGeneratorController extends Controller
{
    /**
     * Handles the full flow of generating a quiz from user inputs via AI.
     */
    public function generateFromPrompt(Request $request)
    {
        $data = $this->validateInputs($request);

        $prompt = $this->buildPrompt($data['subject'], $data['count'], $data['answers']);

        $qcm = $this->callMistral($prompt);

        if (!is_array($qcm) || empty($qcm)) {
            return redirect()->route('knowledge.generate')
                ->withErrors(['qcm' => 'La génération du bilan a échoué. Essayez un sujet plus précis.']);
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

        Ta mission est de créer un QCM structuré avec les règles suivantes :

        1. Il doit contenir exactement {$count} questions.
        2. Chaque question doit proposer **exactement {$answers} choix de réponse** (PAS PLUS, PAS MOINS).
        3. Le format des réponses doit être un tableau comme celui-ci : "options": ["Réponse A", "Réponse B", ...]
        4. Chaque question doit inclure 4 champs :
            - "question" : l'intitulé de la question
            - "options" : un tableau de {$answers} propositions
            - "answer" : la bonne réponse
            - "difficulty" : facile, moyen ou difficile

        5. La répartition des difficultés doit être :
            - 30% des questions faciles
            - 40% des questions moyennes
            - 30% des questions difficiles

        Tu dois retourner UNIQUEMENT un tableau JSON strictement valide, **sans aucun texte autour**.

        Voici un exemple avec {$answers} réponses :
        [
            {
                "question": "Que fait la commande `php artisan migrate` en Laravel ?",
                "options": ["Créer une table", "Supprimer une table", "Exécuter les migrations", "Créer un contrôleur"],
                "answer": "Exécuter les migrations",
                "difficulty": "facile"
            }
        ]

        Maintenant, génère le QCM. Ne mets aucun texte ou explication, retourne uniquement du JSON.
    EOT;
    }

    /**
     * Sends the prompt to the Mistral API and returns the decoded QCM.
     */
    private function callMistral(string $prompt): ?array
    {
        try {
            $mistral = new \App\Services\MistralApiService();
            $response = $mistral->generateQuestionnaire([
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            return json_decode($response['choices'][0]['message']['content'] ?? '', true);

        } catch (\Exception $e) {
            \Log::error('Erreur IA : ' . $e->getMessage());

            return null;
        }
    }



    /**
     * Generates a QCM using the Mistral AI based on the given subject and question count.
     * Validates inputs, sends a prompt to the API, and stores the result in session.
     * Redirects to the preview page if successful, or back to the form with an error on failure.
     */
    /*public function generateFromPrompt(Request $request)
    {
        $subject = $request->input('subject');
        $count = (int)$request->input('count', 0);
        $answers = (int)$request->input('answers', 4);

        if ($subject && $count > 0) {
            $request->validate([
                'subject' => 'required|string|max:100',
                'count' => 'required|integer|min:1|max:10',
                'answers' => 'required|integer|min:2|max:6',
            ]);

            $prompt = <<<EOT
            Tu es un générateur de bilan de compétences.

            Génère un QCM de {$count} questions sur les sujets suivants : {$subject}.

            Voici les règles à respecter :
            - Chaque question propose {$answers} choix de réponse.
            - Structure des difficultés : 30% facile, 40% moyen, 30% difficile.
            - Chaque question doit contenir : "question", "options", "answer", "difficulty" (facile/moyen/difficile).

            Retourne uniquement un tableau JSON structuré comme ceci :

            [
                {
                    "question": "Quel est le rôle d’un contrôleur en Laravel ?",
                    "options": ["A", "B", "C", "D"],
                    "answer": "B",
                    "difficulty": "moyen"
                }
            ]

            Ne retourne que du JSON.
            EOT;

            try {
                $mistral = new MistralApiService();
                $response = $mistral->generateQuestionnaire([
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ]
                ]);

                $iaResponse = $response['choices'][0]['message']['content'] ?? null;

                $qcm = json_decode($iaResponse, true);

                if (!is_array($qcm) || empty($qcm)) {
                    return redirect()->route('knowledge.generate')
                        ->withErrors(['qcm' => 'La génération du bilan a échoué. Essayez un sujet plus précis.']);
                }

                session([
                    'qcm' => json_encode($qcm),
                    'subject' => $subject,
                    'question_count' => $count
                ]);

                return redirect()->route('knowledge.preview');

            } catch (\Exception $e) {
                Log::error('Erreur IA : ' . $e->getMessage());

                return redirect()->route('knowledge.generate')
                    ->withErrors(['qcm' => 'Une erreur est survenue : ' . $e->getMessage()]);
            }
        }

        return redirect()->route('knowledge.generate');
    }*/
}
