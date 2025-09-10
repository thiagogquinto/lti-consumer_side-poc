<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Platform\Platform;

$platform = new Platform(
    'MyPlatformIdentifier',                       // [required] identifier
    'MyPlatform',                             // [required] name
    'http://localhost:9000',                     // [required] audience
);