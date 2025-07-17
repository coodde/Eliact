<?php

namespace Coodde\Eliact\Drivers;

use Coodde\Eliact\Contracts\AiDriverInterface;

class GoogleDriver implements AiDriverInterface
{
    public function __construct(protected string $apiKey, protected string $model = 'gemini-1.5-pro') {}

    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array
    {
        // TODO: implement API call to Google Gemini models
        return 'Mocked response from Google Gemini';
    }
}
