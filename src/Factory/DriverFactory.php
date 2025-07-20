<?php

namespace Coodde\Eliact\Factory;

use Coodde\Eliact\Contracts\AiDriverInterface;
use Coodde\Eliact\Drivers\OpenAiDriver;
use Coodde\Eliact\Drivers\DeepSeekDriver;
use Coodde\Eliact\Drivers\ClaudeDriver;
use Coodde\Eliact\Drivers\MistralDriver;
use Coodde\Eliact\Drivers\OllamaDriver;
use InvalidArgumentException;

class DriverFactory
{
    public static function make(string $provider, string $apiKey, array $config = []): AiDriverInterface
    {
        return match (strtolower($provider)) {
            'openai' => new OpenAiDriver($apiKey, $config['model'] ?? 'gpt-3.5-turbo'),
            'deepseek' => new DeepSeekDriver($apiKey, $config['model'] ?? 'deepseek-chat'),
            'claude' => new ClaudeDriver($apiKey, $config['model'] ?? 'claude-3-sonnet'),
            'mistral' => new MistralDriver($apiKey, $config['model'] ?? 'mistral-7b-instruct'),
            'ollama' => new OllamaDriver($apiKey, $config['model'] ?? 'llama2'),
            default => throw new InvalidArgumentException("Unsupported AI provider: $provider")
        };
    }

    public static function supportedModels(): array
    {
        return [
            'openai' => ['gpt-3.5-turbo', 'gpt-4'],
            'deepseek' => ['deepseek-chat'],
            'claude' => ['claude-3-sonnet', 'claude-3-haiku'],
            'mistral' => ['mistral-7b-instruct', 'mixtral-8x7b'],
            'ollama' => ['llama2', 'mistral', 'gemma']
        ];
    }
}
