<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class MistralApiService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    //Constructeur de la classe MistralApiService
    //Initialise le client HTTP et récupère la clé API depuis le fichier .env
    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('MISTRAL_API_KEY');
        $this->baseUrl = 'https://api.mistral.ai/v1/chat/completions';
    }

    /**
     * Envoie un message à l'API Mistral pour générer un QCM.
     *
     * @param array $data Contient un tableau de messages (ex: prompt de l'utilisateur).
     * @return array Résultat brut retourné par l'API (inclut généralement un champ 'choices' avec la réponse de l'IA).
     *
     * Cette méthode utilise Guzzle pour envoyer une requête POST vers l'API de Mistral.
     * Elle gère aussi les erreurs et les logs pour faciliter le débogage.
     */
    public function generateQuestionnaire(array $data)
    {

        $requestBody = [
            "model" => "mistral-small",
            "temperature" => 0.7,
            "max_tokens" => 1024,
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


