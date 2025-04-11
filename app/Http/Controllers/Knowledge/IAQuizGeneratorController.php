<?php

namespace App\Http\Controllers\Knowledge;

use App\Http\Controllers\Controller;
use App\Services\MistralApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IAQuizGeneratorController extends Controller
{
    /**
     * Generates a QCM using the Mistral AI based on the given subject and question count.
     * Validates inputs, sends a prompt to the API, and stores the result in session.
     * Redirects to the preview page if successful, or back to the form with an error on failure.
     */
    public function generateFromPrompt(Request $request)
    {
        $subject = $request->input('subject');
        $count = (int)$request->input('count', 0);

        if ($subject && $count > 0) {
            $request->validate([
                'subject' => 'required|string|max:100',
                'count' => 'required|integer|min:1|max:10',
            ]);

            $prompt = <<<EOT
            Génère un QCM de {$count} questions sur le sujet suivant : {$subject}.
            Retourne uniquement un tableau JSON structuré comme ceci :

            [
                {
                    "question": "Texte de la question",
                    "options": ["choix A", "choix B", "choix C", "choix D"],
                    "answer": "Réponse correcte"
                }
            ]

            Ne retourne que du JSON sans texte supplémentaire.
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
                        ->withErrors(['qcm' => 'La génération du QCM a échoué. Essayez un sujet plus précis.']);
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
    }
}
