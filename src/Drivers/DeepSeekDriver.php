<?php

namespace Coodde\Eliact\Drivers;

use Coodde\Eliact\Contracts\AiDriverInterface;

class DeepSeekDriver implements AiDriverInterface
{
    public function __construct(protected string $apiKey, protected string $model = 'deepseek-chat') {}

    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array
    {
        // TODO: implement API call to DeepSeek models
        return 'Mocked response from DeepSeek';
    }
}
