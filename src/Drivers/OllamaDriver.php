<?php

namespace Coodde\Eliact\Drivers;

use Coodde\Eliact\Contracts\AiDriverInterface;
use Coodde\Eliact\Constants\Drivers;
use Coodde\Eliact\Constants\InputTypes;
use Coodde\Eliact\Support\SchemaValidator;
use GuzzleHttp\Client;
use InvalidArgumentException;
use RuntimeException;

class OllamaDriver implements AiDriverInterface
{
    public const DEFAULT_URL = 'http://localhost:11434';

    protected Client $client;
    protected SchemaValidator $validator;

    public function __construct(
        protected string $model = 'llama3',
        protected string $baseUrl = self::DEFAULT_URL
    ) {
        if (!in_array($model, Drivers::MODELS[Drivers::OLLAMA])) {
            throw new InvalidArgumentException("Not supported or wrong model name: $model");
        }

        $this->client = new Client(['base_uri' => rtrim($baseUrl, '/') . '/']);
        $this->validator = new SchemaValidator();
    }

    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array
    {
        $messages = [['role' => 'user', 'content' => $prompt]];

        if (isset($options['system'])) {
            array_unshift($messages, ['role' => 'system', 'content' => $options['system']]);
        }

        if ($schema) {
            $messages[] = [
                'role' => 'system',
                'content' => 'Please return JSON with the following keys and types: ' .
                    json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            ];
        }

        $payload = [
            'model'   => $this->model,
            'messages' => $messages,
            'stream'  => false,
            'options' => ['temperature' => $options['temperature'] ?? 0.4],
        ];

        if ($schema) {
            $payload['format'] = 'json';
        }

        $response = $this->client->post('api/chat', [
            'headers' => ['Content-Type' => 'application/json'],
            'json'    => $payload,
        ]);

        $data   = json_decode((string) $response->getBody(), true);
        $output = $data['message']['content'] ?? '';

        if ($schema) {
            $decoded = json_decode($output, true);
            return is_array($decoded) ? $this->validator->validate($decoded, $schema) : ['_raw' => $output];
        }

        return $output;
    }

    public function chatWithImage(string $imagePath, string $prompt, array $options = []): string|array
    {
        $visionModels = ['llava', 'bakllava'];
        if (!in_array($this->model, $visionModels)) {
            throw new RuntimeException("Model {$this->model} does not support image input.");
        }

        $imageBase64 = base64_encode(file_get_contents($imagePath));

        $payload = [
            'model'   => $this->model,
            'messages' => [
                [
                    'role'    => 'user',
                    'content' => $prompt,
                    'images'  => [$imageBase64],
                ],
            ],
            'stream'  => false,
            'options' => ['temperature' => $options['temperature'] ?? 0.4],
        ];

        $response = $this->client->post('api/chat', [
            'headers' => ['Content-Type' => 'application/json'],
            'json'    => $payload,
        ]);

        $data = json_decode((string) $response->getBody(), true);
        return $data['message']['content'] ?? '';
    }
}
