<?php

namespace Coodde\Eliact;

use Coodde\Eliact\Contracts\AiDriverInterface;

class Eliact
{
    protected AiDriverInterface $driver;

    public function __construct(AiDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Основна точка виклику: парсинг тексту в структуровані поля
     *
     * @param string $text
     * @param array<string, string> $schema типізована структура (name => 'string', email => 'email'...)
     * @param array $options
     * @return array
     */
    public function parseText(string $text, array $schema, array $options = []): array
    {
        $this->driver->chat($text, $schema, );
    }

    public function parseImage(string $imagePath, array $schema, array $options = []): array
    {
        $text = (new ImageAdapter())->toText($imagePath, $options);
        return $this->parse($text, $schema, $options);
    }

    public function parseAudio(string $audioPath, array $schema, array $options = []): array
    {
        $text = (new AudioAdapter())->toText($audioPath, $options);
        return $this->parse($text, $schema, $options);
    }

    public function parseHtml(string $htmlOrUrl, array $schema, array $options = []): array
    {
        $text = (new HtmlAdapter())->toText($htmlOrUrl, $options);
        return $this->parse($text, $schema, $options);
    }
}
