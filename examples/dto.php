<?php

use Coodde\Eliact\Eliact;
use Coodde\Eliact\Factory\DriverFactory;

require __DIR__ . '/../vendor/autoload.php';

class ContactDto
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone = null
    ) {}
}

$driver = DriverFactory::make('openai', 'your-api-key');
$eliact = new Eliact($driver);

$dto = $eliact->parseToDto("Я — Ігор. Телефон: +380991112233. Пошта: igor@domain.com", [
    'name' => 'string',
    'email' => 'email',
    'phone' => 'phone'
], ContactDto::class);

print_r($dto);