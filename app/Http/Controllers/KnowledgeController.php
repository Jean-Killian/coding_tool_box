<?php

namespace App\Http\Controllers;

use App\Services\MistralApiService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class KnowledgeController extends Controller
{
    /**
     * Affiche la page de génération de QCM IA et gère l'envoi du formulaire.
     *
     * - Si aucun paramètre n’est fourni : affiche la page vide
     * - Si un sujet et un nombre de questions sont fournis : envoie un prompt à l’API Mistral
     * - Récupère et vérifie la réponse de l’IA
     * - Affiche le QCM (texte brut + version formatée si possible)
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $subject = $request->input('subject');
        $count = (int)$request->input('count', 0);

        $iaResponse = null;

        if ($subject && $count > 0) {
            $request->validate([
                'subject' => 'required|string|max:100',
                'count' => 'required|integer|min:1|max:20',
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

                $iaResponse = $response['choices'][0]['message']['content'] ?? 'No content received.';

            } catch (\Exception $e) {
                $iaResponse = 'Erreur lors de la génération : ' . $e->getMessage();
            }
        }

        return view('pages.knowledge.index', [
            'iaResponse' => $iaResponse,
            'subject' => $subject,
            'count' => $count
        ]);
    }
}
