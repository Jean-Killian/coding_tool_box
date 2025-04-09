<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MistralApiService;
use Illuminate\Support\Facades\Log;

class QcmController extends Controller
{
    /**
     * Génère un QCM via l'API Mistral et l'affiche à l'étudiant.
     */
    public function show()
    {
        $prompt = <<<EOT
        Génère un QCM de 5 questions sur le framework Laravel.

        Retourne UNIQUEMENT un tableau JSON comme ceci :

        [
            {
                "question": "Quel est le mot-clé pour définir une fonction en PHP ?",
                "options": ["define", "func", "function", "method"],
                "answer": "function"
            }
        ]

        Le JSON doit être valide, sans texte autour, sans explication, sans indentation excessive.
        EOT;

        try {
            $mistral = new MistralApiService();
            $response = $mistral->generateQuestionnaire([
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            $json = $response['choices'][0]['message']['content'] ?? null;
            $qcm = json_decode($json, true);

            if (!is_array($qcm)) {
                throw new \Exception("JSON invalide.");
            }

            foreach ($qcm as $question) {
                if (
                    !isset($question['question'], $question['options'], $question['answer']) ||
                    count($question['options']) !== 4
                ) {
                    throw new \Exception("QCM mal structuré.");
                }
            }

            session(['qcm' => $qcm]);

            return view('knowledge.qcm', ['qcm' => $qcm]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du QCM : ' . $e->getMessage());
            return response('Erreur : ' . $e->getMessage(), 500);
        }
    }
}
