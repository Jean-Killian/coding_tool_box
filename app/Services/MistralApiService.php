<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class MistralApiService
{
    protected Client $client;
    protected string $apiKey;
    protected string $baseUrl = 'https://api.mistral.ai/v1/chat/completions';

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('MISTRAL_API_KEY');
    }

    /**
     * Sends a quiz generation request to the Mistral API.
     *
     * - Validates input messages
     * - Builds the request payload
     * - Sends POST request and handles errors
     *
     * @param array $data User messages to send to the AI
     * @return array Parsed JSON response from the API
     */
    public function generateQuestionnaire(array $data): array
    {
        if (empty($data['messages']) || !is_array($data['messages'])) {
            throw new \InvalidArgumentException("Missing or invalid 'messages' array.");
        }

        $body = $this->buildRequestBody($data['messages']);

        try {
            Log::info('Sending request to Mistral API', ['body' => $body]);

            $response = $this->client->post($this->baseUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $body,
            ]);

            $content = $response->getBody()->getContents();
            Log::info('Mistral API response', ['content' => $content]);

            return json_decode($content, true);

        } catch (RequestException $e) {
            $message = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();

            Log::error('Mistral API error', ['error' => $message]);
            throw new \Exception("Mistral API request failed: " . $message);
        }
    }

    /**
     * Builds the API request body.
     *
     * @param array $messages User messages for the prompt
     * @return array Formatted request payload
     */
    protected function buildRequestBody(array $messages): array
    {
        return [
            'model' => 'mistral-small',
            'temperature' => 0.7,
            'max_tokens' => 2048,
            'stream' => false,
            'messages' => $messages,
        ];
    }
}



