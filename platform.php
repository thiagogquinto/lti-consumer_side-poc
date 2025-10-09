<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Platform\Platform;

$platform = new Platform(
    'Teste',
    'Consumer',
    'http://localhost:9000',
);