<?php

namespace Coodde\Eliact\Drivers;

use Coodde\Eliact\Contracts\AiDriverInterface;
use Coodde\Eliact\Constants\Drivers;
use Coodde\Eliact\Constants\InputTypes;
use Coodde\Eliact\Constants\Supported;
use Coodde\Eliact\Support\SchemaValidator;
use GuzzleHttp\Client;
use InvalidArgumentException;

class AnthropicDriver implements AiDriverInterface
{
    private const API_URL = 'https://api.anthropic.com/v1/';
    protected Client $client;
    protected SchemaValidator $validator;

    public function __construct(protected string $apiKey, protected string $model = 'claude-3-sonnet')
    {
        if (!in_array($model, Drivers::MODELS[Drivers::ANTHROPIC])) {
            throw new InvalidArgumentException("Not supported or wrong model name: $model");
        }

        $this->apiKey = $apiKey;
        $this->model = $model;
        $this->client = new Client([
            'base_uri' => self::API_URL
        ]);
        $this->validator = new SchemaValidator();
    }

    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array
    {
        if (!Supported::allowedInputPerDriver(InputTypes::TEXT, Drivers::ANTHROPIC, $this->model)) {
            throw new \RuntimeException("Model {$this->model} does not support text input.");
        }

        $payload = [
            'model' => $this->model,
            'max_tokens' => $options['max_tokens'] ?? 1024,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ];

        $response = $this->client->post('messages', [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01'
            ],
            'json' => $payload,
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return $data['content'] ?? $data;
    }

    public function chatWithImage(string $imagePath, string $prompt, array $options = []): string|array
    {
        if (!Supported::allowedInputPerDriver(InputTypes::IMAGE, Drivers::ANTHROPIC, $this->model)) {
            throw new \RuntimeException("Model {$this->model} does not support image input.");
        }

        $imageBase64 = base64_encode(file_get_contents($imagePath));

        $payload = [
            'model' => $this->model,
            'max_tokens' => $options['max_tokens'] ?? 1024,
            'messages' => [
                ['role' => 'user', 'content' => [
                    ['type' => 'text', 'text' => $prompt],
                    ['type' => 'image_base64', 'data' => $imageBase64, 'mime_type' => 'image/png']
                ]]
            ],
        ];

        $response = $this->client->post('messages', [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01'
            ],
            'json' => $payload,
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return $data['content'] ?? $data;
    }
}
