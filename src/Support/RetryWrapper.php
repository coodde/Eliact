<?php

namespace Coodde\Eliact\Support;

use Coodde\Eliact\Contracts\AiDriverInterface;

class RetryWrapper implements AiDriverInterface
{
    public function __construct(
        protected AiDriverInterface $driver,
        protected int $maxAttempts = 2,
        protected int $delayMs = 300
    ) {}

    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array
    {
        for ($attempt = 1; $attempt <= $this->maxAttempts; $attempt++) {
            $response = $this->driver->chat($prompt, $schema, $options);

            if (is_array($response) || json_validate($response)) {
                return $response;
            }

            usleep($this->delayMs * 1000);
        }

        return ['_error' => 'Retry failed: malformed or unstructured response.'];
    }
}
