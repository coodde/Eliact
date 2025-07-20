<?php


namespace Coodde\Eliact\Support;

use Coodde\Eliact\Contracts\AiDriverInterface;
use Psr\SimpleCache\CacheInterface;

class CacheWrapper implements AiDriverInterface
{
    protected AiDriverInterface $driver;
    protected CacheInterface $cache;
    protected int $ttl;

    public function __construct(AiDriverInterface $driver, CacheInterface $cache, int $ttl = 3600)
    {
        $this->driver = $driver;
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array
    {
        $key = $this->getCacheKey($prompt, $schema, $options);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $response = $this->driver->chat($prompt, $schema, $options);
        $this->cache->set($key, $response, $this->ttl);

        return $response;
    }

    protected function getCacheKey(string $prompt, ?array $schema, array $options): string
    {
        return 'eliact_' . md5(json_encode([$prompt, $schema, $options]));
    }
}
