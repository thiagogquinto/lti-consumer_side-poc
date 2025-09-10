<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Security\Key\KeyChainFactory;
use OAT\Library\Lti1p3Core\Security\Key\KeyInterface;

$keyChain = (new KeyChainFactory)->create(
    '1',                                // [required] identifier (used for JWT kid header)
    'myPlatformKeys',                   // [required] key set name (for grouping)
    'file:///home/thiago/.ssh/lti.pub', // [required] public key (file or content)
    'file:///home/thiago/.ssh/lti',     // [optional] private key (file or content)
    KeyInterface::ALG_RS256            // [optional] algorithm (default: RS256)
);