<?php

namespace Coodde\Eliact\Contracts;

interface AiDriverInterface
{
    /**
     * Надсилає запит до LLM та повертає відповідь у вигляді рядка або масиву.
     *
     * @param string $prompt     Запит користувача (неструктурований текст)
     * @param array|null $schema JSON-схема бажаного результату (ключ => тип)
     * @param array $options     Опціональні параметри: system prompt, temperature тощо
     *
     * @return string|array
     */
    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array;
}
