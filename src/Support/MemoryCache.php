<?php

namespace Coodde\Eliact\Support;

use Psr\SimpleCache\CacheInterface;

class MemoryCache implements CacheInterface
{
    private array $storage = [];
    private array $expirations = [];

    public function get($key, $default = null)
    {
        if (!$this->has($key)) return $default;
        return $this->storage[$key];
    }

    public function set($key, $value, $ttl = null): bool
    {
        $this->storage[$key] = $value;
        $this->expirations[$key] = $ttl ? time() + $ttl : null;
        return true;
    }

    public function delete($key): bool
    {
        unset($this->storage[$key], $this->expirations[$key]);
        return true;
    }

    public function clear(): bool
    {
        $this->storage = [];
        $this->expirations = [];
        return true;
    }

    public function getMultiple($keys, $default = null): iterable
    {
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = $this->get($key, $default);
        }
        return $results;
    }

    public function setMultiple($values, $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }
        return true;
    }

    public function deleteMultiple($keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    public function has($key): bool
    {
        if (!array_key_exists($key, $this->storage)) return false;
        if (!isset($this->expirations[$key])) return true;
        if ($this->expirations[$key] < time()) {
            $this->delete($key);
            return false;
        }
        return true;
    }
}
