<?php

<?php

use Coodde\Eliact\Eliact;
use Coodde\Eliact\Factory\DriverFactory;

require __DIR__ . '/../vendor/autoload.php';

$driver = DriverFactory::make('openai', 'your-api-key');
$eliact = new Eliact($driver);

$result = $eliact->parse('Я хочу бути frontend розробником', [
  'role' => 'choice'
], [
  'schema' => [
    '__choices__' => [
      'role' => ['frontend', 'backend', 'fullstack']
    ]
  ]
]);

print_r($result);
