<?php

namespace Coodde\Eliact\Drivers;

use Coodde\Eliact\Contracts\AiDriverInterface;

class OllamaDriver implements AiDriverInterface
{
    public function __construct(protected string $model = 'llama3') {}

    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array
    {
        // TODO: implement local Ollama call (e.g., http://localhost:11434)
        return 'Mocked response from Ollama';
    }
}
