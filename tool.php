<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Tool\Tool;

$tool = new Tool(
    '0CaWYNBvVaxAleO',               
    'MoodleAsProvider',
    'http://127.0.0.1/moodle/enrol/lti/launch.php',
    'http://127.0.0.1/moodle/enrol/lti/login.php?id=06c4ccff21ec19a74ec998be722afbd67ed6195141440272f2928e624e16',
    'http://127.0.0.1/moodle/enrol/lti/launch.php',
    'http://127.0.0.1/moodle/enrol/lti/launch_deeplink.php'
);