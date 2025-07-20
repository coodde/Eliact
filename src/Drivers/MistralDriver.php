<?php

namespace Coodde\Eliact\Drivers;

use Coodde\Eliact\Contracts\AiDriverInterface;

class MistralDriver implements AiDriverInterface
{
    public function __construct(protected string $apiKey = '', protected string $model = 'mistral-7b') {}

    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array
    {
        // TODO: implement call to hosted Mistral or local Ollama/Mistral API
        return 'Mocked response from Mistral';
    }
}
