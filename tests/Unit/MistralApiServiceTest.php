<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\MistralApiService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use Mockery;

class MistralApiServiceTest extends TestCase
{
    /**
     * Test unitaire : vérifie que la méthode generateQuestionnaire fonctionne quand l'API répond correctement.
     *
     * Ce test n'appelle pas l'API réelle. Il utilise un mock pour simuler une réponse de l'API.
     * On vérifie que le service retourne bien les données attendues (contenu du message généré).
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

        $service = new MistralApiService();
        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($service, $mockClient);

        $result = $service->generateQuestionnaire(['messages' => [['role' => 'user', 'content' => 'Génère un questionnaire sur la programmation.']]]);
        $this->assertEquals('Voici un QCM généré...', $result['choices'][0]['message']['content']);
    }

    /**
     * Test unitaire : vérifie que la méthode generateQuestionnaire gère bien une erreur de l'API.
     *
     * Ce test simule une erreur 400 (Bad Request) et vérifie que le service lance une exception avec un message clair.
     * Il permet de s'assurer que les erreurs API sont bien interceptées et traitées proprement.
     */
    public function testGenerateQuestionnaireFailure()
    {
        $mockClient = Mockery::mock(Client::class);

        $mockClient->shouldReceive('post')
            ->once()
            ->andThrow(RequestException::create(new \GuzzleHttp\Psr7\Request('POST', 'test'),
                new Response(400, [], json_encode(['error' => 'Bad Request']))));

        $service = new MistralApiService();
        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($service, $mockClient);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Erreur de l'API : {\"error\":\"Bad Request\"}");

        $service->generateQuestionnaire(['messages' => [['role' => 'user', 'content' => 'Génère un questionnaire sur la programmation.']]]);
    }
}
