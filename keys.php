<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Security\Jwks\Exporter\Jwk\JwkRS256Exporter;
use OAT\Library\Lti1p3Core\Security\Key\KeyChainFactory;
use OAT\Library\Lti1p3Core\Security\Key\KeyInterface;
use OAT\Library\Lti1p3Core\Security\Key\KeyChainRepository;

$keyChain = (new KeyChainFactory)->create(
    '1',                                // [required] identifier (used for JWT kid header)
    'myPlatformKeys',                   // [required] key set name (for grouping)
    'file://home/user/.ssh/id_rsa.pub', // [required] public key (file or content)
    'file://home/user/.ssh/id_rsa',     // [optional] private key (file or content)
    KeyInterface::ALG_RS256            // [optional] algorithm (default: RS256)
);

$keyChainRepository = new KeyChainRepository();
$keyChainRepository->addKeyChain($keyChain);

return $keyChainRepository;