# Eliact - LLM-based structered parsing

**Eliact** — AI-powered structured input parser for PHP. Extracts names, emails, dates and more from natural language using LLMs.

## ✨ Features
- Support for multiple AI providers (OpenAI, DeepSeek, Claude, Mistral, Ollama)
- Flexible schema-based extraction (JSON → validated array)
- DTO hydration (convert to custom objects)
- Driver fallback with `ChainDriver`
- Caching with PSR-16 or in-memory (`MemoryCache`)

## 🚀 Basic usage
```php
$driver = DriverFactory::make('openai', 'sk-...');
$eliact = new Eliact($driver);

$data = $eliact->parse("My name is John and my email is john@example.com", [
    'name' => 'string',
    'email' => 'email'
]);
```

## 🧱 With DTO
```php
class ContactDto {
    public function __construct(
        public string $name,
        public string $email
    ) {}
}

$dto = $eliact->parseToDto("I'm Alice. My email is alice@mail.com", [
    'name' => 'string',
    'email' => 'email'
], ContactDto::class);
```

## 🔁 Chain + Cache example
```php
$chain = new ChainDriver([
    DriverFactory::make('deepseek', 'xxx'),
    DriverFactory::make('openai', 'yyy')
]);

$cached = new CacheWrapper($chain, new MemoryCache());
$eliact = new Eliact($cached);
```

## 📦 Installation
```bash
composer require coodde/eliact
```

## ✅ Supported Types
- string
- email
- date (parsed to Y-m-d)
- int
- float
- bool
- phone
- url
