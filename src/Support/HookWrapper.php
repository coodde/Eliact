<?php

namespace Coodde\Eliact\Support;

use Coodde\Eliact\Contracts\AiDriverInterface;

class HookWrapper implements AiDriverInterface
{
    protected ?\Closure $before = null;
    protected ?\Closure $after = null;

    public function __construct(protected AiDriverInterface $driver) {}

    public function before(callable $callback): static
    {
        $this->before = $callback;
        return $this;
    }

    public function after(callable $callback): static
    {
        $this->after = $callback;
        return $this;
    }

    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array
    {
        if ($this->before) {
            ($this->before)($prompt, $schema, $options);
        }

        $response = $this->driver->chat($prompt, $schema, $options);

        if ($this->after) {
            ($this->after)($response);
        }

        return $response;
    }
}
