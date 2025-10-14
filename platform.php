<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Platform\Platform;

$platform = new Platform(
    'Teste',
    'Consumer',
    'https://e0453dc34cc9.ngrok-free.app',
);