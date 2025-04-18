<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\QuizAIService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use Mockery;

class MistralApiServiceTest extends TestCase
{
    /**
     * Test Mistral API success with mocked response
     */
    public function testGenerateQuestionnaireSuccess()
    {
        $mockClient = Mockery::mock(Client::class);

        $mockResponse = new Response(200, [], json_encode([
            'choices' => [
                [
                    'message' => [
                        'role' => 'assistant',
                        'content' => 'Voici un QCM généré...'
                    ]
                ]
            ]
        ]));

        $mockClient->shouldReceive('post')
            ->once()
            ->andReturn($mockResponse);

        $service = new QuizAIService();
        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($service, $mockClient);

        $result = $service->generateQuestionnaire(['messages' => [['role' => 'user', 'content' => 'Génère un questionnaire sur la programmation.']]]);
        $this->assertEquals('Voici un QCM généré...', $result['choices'][0]['message']['content']);
    }

    /**
     * Test Mistral API error handling with mocked exception
     */
    public function testGenerateQuestionnaireFailure()
    {
        $mockClient = Mockery::mock(Client::class);

        $mockClient->shouldReceive('post')
            ->once()
            ->andThrow(RequestException::create(new \GuzzleHttp\Psr7\Request('POST', 'test'),
                new Response(400, [], json_encode(['error' => 'Bad Request']))));

        $service = new QuizAIService();
        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($service, $mockClient);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Erreur de l'API : {\"error\":\"Bad Request\"}");

        $service->generateQuestionnaire(['messages' => [['role' => 'user', 'content' => 'Génère un questionnaire sur la programmation.']]]);
    }
}
