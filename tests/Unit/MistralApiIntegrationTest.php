<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\MistralApiService;

class MistralApiIntegrationTest extends TestCase
{
    /**
     * Test d'intégration : vérifie que l'API Mistral répond correctement à une vraie requête.
     *
     * Ce test utilise une vraie clé API et un vrai appel réseau (pas de mock).
     * Il vérifie que la réponse contient bien le format attendu (champ 'choices' avec un message généré).
     */
    public function testGenerateQuestionnaireIntegration()
    {
        $service = new MistralApiService();

        $result = $service->generateQuestionnaire([
            'messages' => [
                ['role' => 'user', 'content' => 'Génère un questionnaire sur la programmation.']
            ]
        ]);

        $this->assertArrayHasKey('choices', $result);
        $this->assertNotEmpty($result['choices']);
        $this->assertArrayHasKey('message', $result['choices'][0]);
        $this->assertArrayHasKey('content', $result['choices'][0]['message']);
    }
}

