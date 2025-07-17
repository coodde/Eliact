<?php

namespace Coodde\Eliact\Constants;

use Coodde\Eliact\Contracts\Drivers;
use Coodde\Eliact\Contracts\InputTypes;

class Supported
{
    public static function allowedInputPerDriver(string $input, string $provider, string $model): bool
    {
        return in_array($input, self::getInputPerDriver($provider, $model));
    }

    public static function getInputPerDriver(string $provider, string $model): array
    {
        $provider = strtolower($provider);
        $model = strtolower($model);

        return match ($provider) {
            Drivers::OPENAI => match ($model) {
                'gpt-4o'           => [InputTypes::TEXT, InputTypes::IMAGE, InputTypes::AUDIO],
                'gpt-4'            => [InputTypes::TEXT],
                'gpt-3.5-turbo'    => [InputTypes::TEXT],
                default            => [InputTypes::TEXT],
            },

            Drivers::ANTHROPIC => match ($model) {
                'claude-3-sonnet',
                'claude-3-opus'    => [InputTypes::TEXT, InputTypes::IMAGE],
                'claude-3-haiku'   => [InputTypes::TEXT],
                default            => [InputTypes::TEXT],
            },

            Drivers::GOOGLE => match ($model) {
                'gemini-1.5-pro'   => [InputTypes::TEXT, InputTypes::IMAGE, InputTypes::AUDIO],
                'gemini-1.5-flash' => [InputTypes::TEXT, InputTypes::IMAGE],
                default            => [InputTypes::TEXT],
            },

            Drivers::DEEPSEEK => match ($model) {
                'deepseek-vl'      => [InputTypes::TEXT, InputTypes::IMAGE],
                default            => [InputTypes::TEXT],
            },

            Drivers::MISTRAL => match ($model) {
                'mistral-7b',
                'mixtral-8x7b' => [InputTypes::TEXT],
                default        => [InputTypes::TEXT],
            },

            Drivers::OLLAMA => match ($model) {
                'llama3',
                'gemma' => [InputTypes::TEXT],
                'llava',
                'bakllava' => [InputTypes::TEXT, InputTypes::IMAGE],
                default        => [InputTypes::TEXT],
            },

            default => [InputTypes::TEXT],
        };
    }
}
