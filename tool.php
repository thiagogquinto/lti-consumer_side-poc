<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Tool\Tool;

$tool = new Tool(
    'toolIdentifier',               // [required] identifier
    'MoodleAsProvider',                     // [required] name
    'http://127.0.0.1/moodle/enrol/lti/launch.php',             // [required] audience
    'http://127.0.0.1/moodle/enrol/lti/login.php?id=0a178c63e27266133b99de19e75a9dad026454490c4f935b4b4d542b7270',   // [required] OIDC initiation url
    'http://127.0.0.1/moodle/enrol/lti/launch.php',      // [optional] default tool launch url
    'http://127.0.0.1/moodle/enrol/lti/launch_deeplink.php' // [optional] DeepLinking url
);