<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class MistralApiService
{
    /**
     * Guzzle HTTP client used to make requests to the Mistral API.
     */
    protected $client;
    /**
     * API key retrieved from the .env configuration.
     */
    protected $apiKey;

    /**
     * Base URL for the Mistral chat completion endpoint.
     */
    protected $baseUrl;

    /**
     * Initializes the HTTP client and retrieves the Mistral API key from environment variables.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('MISTRAL_API_KEY');
        $this->baseUrl = 'https://api.mistral.ai/v1/chat/completions';
    }

    /**
     * Sends a request to the Mistral API to generate a quiz based on user input.
     *
     * - Builds a JSON request with model settings and messages.
     * - Sends a POST request using Guzzle.
     * - Logs both request and response for debugging.
     * - Handles errors and returns the decoded API response.
     *
     * @param array $data Array containing 'messages' for the chat prompt.
     * @return array Decoded JSON response from Mistral API.
     * @throws \Exception If request fails or input is invalid.
     */
    public function generateQuestionnaire(array $data)
    {

        $requestBody = [
            "model" => "mistral-small",
            "temperature" => 0.7,
            "max_tokens" => 2048,
            "stream" => false,
            "messages" => []
        ];

        if (isset($data['messages']) && is_array($data['messages'])) {
            $requestBody['messages'] = $data['messages'];
        } else {
            throw new \Exception("Aucun message utilisateur fourni pour générer le QCM.");
        }

        try {
            Log::info('Sending request to Mistral API with data:', $requestBody);

            $response = $this->client->post($this->baseUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);

            $body = $response->getBody()->getContents();
            Log::info('Réponse de Mistral API :', ['body' => $body]);

            return json_decode($body, true);

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse()->getBody()->getContents();
                Log::error('Error response from Mistral API:', ['error' => $errorResponse]);
                throw new \Exception("Erreur de l'API : " . $errorResponse);
            } else {
                Log::error('Request failed:', ['message' => $e->getMessage()]);
                throw new \Exception("Erreur de connexion : " . $e->getMessage());
            }
        }
    }
}


