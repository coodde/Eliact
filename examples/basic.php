<?php

use Coodde\Eliact\Eliact;
use Coodde\Eliact\Factory\DriverFactory;
use Coodde\Eliact\Constants\Drivers;

require __DIR__ . '/../vendor/autoload.php';

$driver = DriverFactory::make(Drivers::OPENAI, 'your-api-key');
$eliact = new Eliact($driver);

$result = $eliact->parseText("Привіт, мене звати Олександр і моя пошта olex@example.com", [
    'name' => 'string',
    'email' => 'email'
]);

print_r($result);
