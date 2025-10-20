<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/platforms/sinepe.php';
require_once __DIR__ . '/tools/moodle.php';
require_once __DIR__ . '/platformKeys.php';

use OAT\Library\Lti1p3Core\Registration\Registration;

$clientId = '0CaWYNBvVaxAIeO';
$deploymentId = '1';

$registration = new Registration(
    'registro-moodle-local-01',
    $clientId,
    $platform,
    $tool,
    [$deploymentId],
    $platformKeyChain,
);