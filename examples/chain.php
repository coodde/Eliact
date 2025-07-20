<?php

use Coodde\Eliact\Eliact;
use Coodde\Eliact\Support\ChainDriver;
use Coodde\Eliact\Drivers\DeepSeekDriver;
use Coodde\Eliact\Drivers\OpenAiDriver;

require __DIR__ . '/../vendor/autoload.php';

$driver = new ChainDriver([
    new DeepSeekDriver('deepseek-key'),
    new OpenAiDriver('openai-key')
]);

$eliact = new Eliact($driver);

$result = $eliact->parse("Привіт, мене звати Марія. Моя пошта — maria@mail.com", [
    'name' => 'string',
    'email' => 'email'
]);

print_r($result);
