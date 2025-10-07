<?php

$keyChainRepository = require_once __DIR__ . '/../keys.php';

use OAT\Library\Lti1p3Core\Security\Jwks\Exporter\JwksExporter;
use OAT\Library\Lti1p3Core\Security\Jwks\Server\JwksRequestHandler;

$handler = new JwksRequestHandler(new JwksExporter($keyChainRepository));

$response = $handler->handle('myPlatformKeys');

header('Content-Type: application/json');
echo $response->getBody();
