<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/platform.php';
require_once __DIR__ . '/tool.php';
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