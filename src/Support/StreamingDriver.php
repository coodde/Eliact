<?php

namespace Coodde\Eliact\Support;

use Coodde\Eliact\Contracts\AiDriverInterface;

class StreamingDriver implements AiDriverInterface
{
    protected \Closure $onToken;

    public function __construct(
        protected AiDriverInterface $driver,
        callable $onToken
    ) {
        $this->onToken = $onToken;
    }

    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array
    {
        $buffer = '';
        $streamDriver = $this->driver;

        $response = $streamDriver->chat($prompt, $schema, array_merge($options, [
            'stream' => true,
            'on_token' => function (string $token) use (&$buffer) {
                $buffer .= $token;
                ($this->onToken)($token);
            }
        ]));

        return $response ?? $buffer;
    }
}
