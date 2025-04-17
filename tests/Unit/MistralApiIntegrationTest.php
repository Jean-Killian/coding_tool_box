<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\QuizAIService;

class MistralApiIntegrationTest extends TestCase
{
    /**
     * Integration test: checks if Mistral API responds correctly.
     */
    public function testGenerateQuestionnaireIntegration()
    {
        $service = new QuizAIService();

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

