<?php

namespace Coodde\Eliact\Contracts;

interface InputAdapterInterface
{
    /**
     * Приймає довільне джерело (string, stream, file) і повертає plain text.
     *
     * @param mixed $source — може бути шлях до файлу, URL, stream, text...
     * @param array $options — додаткові налаштування
     * @return string — готовий текст для аналізу
     */
    public function toText(mixed $source, array $options = []): string;

    public function prepareMessage(): void;
}
