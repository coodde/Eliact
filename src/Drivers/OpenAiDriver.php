<?php

namespace Coodde\Eliact\Drivers;

use Coodde\Eliact\Contracts\AiDriverInterface;
use Coodde\Eliact\Constants\Drivers;
use Coodde\Eliact\Constants\InputTypes;
use Coodde\Eliact\Constants\Supported;
use Coodde\Eliact\Support\SchemaValidator;
use GuzzleHttp\Client;
use InvalidArgumentException;

class OpenAiDriver implements AiDriverInterface
{
    private const API_URL = 'https://api.openai.com/v1/';
    protected Client $client;
    protected SchemaValidator $validator;

    public function __construct(protected string $apiKey, protected string $model = 'gpt-3.5-turbo')
    {
        if (!in_array($model, Drivers::MODELS[Drivers::OPENAI])) {
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

        $messages = [
            ['role' => 'user', 'content' => $prompt]
        ];

        if (isset($options['system'])) {
            array_unshift($messages, ['role' => 'system', 'content' => $options['system']]);
        }

        if ($schema) {
            $messages[] = [
                'role' => 'system',
                'content' => "Please return JSON with the following keys and types: " .
                    json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ];
        }

        $response = $this->client->post('chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json'
            ],
            'json' => [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => $options['temperature'] ?? 0.4
            ]
        ]);

        $data = json_decode((string) $response->getBody(), true);
        $output = $data['choices'][0]['message']['content'] ?? '';

        if ($schema) {
            $decoded = json_decode($output, true);
            return is_array($decoded) ? $this->validator->validate($decoded, $schema) : ['_raw' => $output];
        }

        return $output;
    }

    public function chatWithImage(string $imagePath, string $prompt, array $options = []): string|array
    {
        if (!Supported::allowedInputPerDriver(InputTypes::IMAGE, Drivers::OPENAI, $this->model)) {
            throw new \RuntimeException("Model {$this->model} does not support image input.");
        }

        $imageBase64 = base64_encode(file_get_contents($imagePath));

        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => $prompt],
                        ['type' => 'image_url', 'image_url' => ['url' => 'data:image/png;base64,' . $imageBase64]]
                    ]
                ]
            ],
            'max_tokens' => $options['max_tokens'] ?? 1024,
        ];

        $response = $this->http->post('chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'json' => $payload,
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return $data['choices'][0]['message']['content'] ?? $data;
    }

    public function chatWithAudio(string $audioPath, string $prompt, array $options = []): string|array
    {
        if (!Supported::allowedInputPerDriver(InputTypes::AUDIO, Drivers::OPENAI, $this->model)) {
            throw new \RuntimeException("Model {$this->model} does not support audio input.");
        }

        // Note: for audio OpenAI typically uses /audio/transcriptions endpoint
        // Here simplified as sending audio as base64 in the message
        $audioBase64 = base64_encode(file_get_contents($audioPath));

        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => $prompt],
                        ['type' => 'audio_base64', 'data' => $audioBase64, 'mime_type' => 'audio/mpeg']
                    ]
                ]
            ],
            'max_tokens' => $options['max_tokens'] ?? 1024,
        ];

        $response = $this->http->post('chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'json' => $payload,
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return $data['choices'][0]['message']['content'] ?? $data;
    }
}
