<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/platform.php';
require_once __DIR__ . '/tool.php';
require_once __DIR__ . '/keys.php';

use OAT\Library\Lti1p3Core\Registration\Registration;

$clientId = '0CaWYNBvVaxAIeO';
$deploymentId = '1';

$registration = new Registration(
    'registro-moodle-local-01',  // Identificador interno para este registro
    $clientId,                   // Client ID do pop-up
    $platform,                   // O objeto $platform do Passo 2
    $tool,                       // O objeto $tool do Passo 1
    [$deploymentId],             // Deployment ID do pop-up (como um array)
    $keyChain
);