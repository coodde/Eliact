<?php

namespace Coodde\Eliact\Constants;

class Drivers
{
    public const OPENAI     = 'openai';
    public const ANTHROPIC  = 'anthropic';
    public const GOOGLE     = 'google';
    public const DEEPSEEK   = 'deepseek';
    public const MISTRAL    = 'mistral';
    public const OLLAMA     = 'ollama';

    public const MODELS = [
        self::OPENAI => [
            'gpt-4o',
            'gpt-4',
            'gpt-3.5-turbo'
        ],
        self::ANTHROPIC => [
            'claude-3-opus',
            'claude-3-sonnet',
            'claude-3-haiku'
        ],
        self::GOOGLE => [
            'gemini-1.5-pro',
            'gemini-1.5-flash'
        ],
        self::DEEPSEEK => [
            'deepseek-chat',
            'deepseek-vl'
        ],
        self::MISTRAL => [
            'mistral-7b',
            'mixtral-8x7b'
        ],
        self::OLLAMA => [
            'llama3',
            'gemma',
            'llava',
            'bakllava',
        ],
    ];
}
