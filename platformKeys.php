<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Security\Jwks\Exporter\Jwk\JwkRS256Exporter;
use OAT\Library\Lti1p3Core\Security\Key\KeyChainFactory;
use OAT\Library\Lti1p3Core\Security\Key\KeyInterface;
use OAT\Library\Lti1p3Core\Security\Key\KeyChainRepository;

$platformKeyChain = (new KeyChainFactory)->create(
    '1',                                // [required] identifier (used for JWT kid header)
    'myPlatformKeys',                   // [required] key set name (for grouping)
    'file://' . __DIR__ . '/jwks/keys/lti.pem',         // [required] path to public key file (PEM)
    'file://' . __DIR__ . '/jwks/keys/lti',      // [optional] path to private key file (PEM, PKCS#1 or PKCS#8)
    KeyInterface::ALG_RS256            // [optional] algorithm (default: RS256)
);

// $jwkExport = (new JwkRS256Exporter())->export($platformKeyChain);

$keyChainRepository = new KeyChainRepository();
$keyChainRepository->addKeyChain($platformKeyChain);

return $keyChainRepository;