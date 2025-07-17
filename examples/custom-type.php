<?php

use Coodde\Eliact\Eliact;
use Coodde\Eliact\Factory\DriverFactory;
use Coodde\Eliact\Support\SchemaValidator;
use Coodde\Eliact\Support\CacheWrapper;
use Coodde\Eliact\Support\MemoryCache;

require __DIR__ . '/../vendor/autoload.php';

// Створюємо кастомний валідаційний тип 'hashtag'
$validator = new SchemaValidator();
$validator->extend('hashtag', function ($value) {
    if (preg_match('/^#[a-zA-Z0-9_]{2,}$/', $value)) {
        return $value;
    }
    return null;
});

// Проксі-драйвер, який підмінює стандартний валідатор
class CustomValidatorDriver implements Coodde\Eliact\Contracts\AiDriverInterface {
    public function __construct(
        protected Coodde\Eliact\Contracts\AiDriverInterface $base,
        protected SchemaValidator $validator
    ) {}

    public function chat(string $prompt, ?array $schema = null, array $options = []): string|array
    {
        $result = $this->base->chat($prompt, $schema, $options);
        if (is_array($result) && $schema) {
            return $this->validator->validate($result, $schema);
        }
        return $result;
    }
}

$driver = DriverFactory::make('openai', 'your-api-key');
$wrapped = new CustomValidatorDriver($driver, $validator);
$eliact = new Eliact(new CacheWrapper($wrapped, new MemoryCache()));

$result = $eliact->parse("Привіт! Мій хештег — #LaravelRocks", [
    'hashtag' => 'hashtag'
]);

print_r($result);
