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

## ⌨️ Supported Input Types
- Text
- URL / HTML
- Image
- Audio

## 🌐 Supported Providers
- OpenAI: gpt-4o, gpt-4, gpt-3.5-turbo
- Anthropic: claude-3-opus, claude-3-sonnet, claude-3-haiku
- Google: gemini-1.5-pro, gemini-1.5-flash
- DeepSeek: deepseek-chat, deepseek-vl
- Mistral: mistral-7b, mixtral-8x7b
- Ollama: llama3, gemma, llava, bakllava

## ✅ Supported Data Types
- bool
- country-code
- credit-card
- date (parsed to Y-m-d)
- datetime
- email
- float
- int
- month
- phone
- safe-string
- string
- time
- url
- uuid
- year
- zip-code
