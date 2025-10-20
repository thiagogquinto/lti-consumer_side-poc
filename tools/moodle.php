<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OAT\Library\Lti1p3Core\Tool\Tool;

$tool = new Tool(
    'Moodle',               
    'MoodleAsProvider',
    'http://localhost/enrol/lti/launch.php',
    'http://localhost/enrol/lti/login.php?id=813f76410c82c343572c4e38b672698c38c55181af9d13a2796859991039',
    'http://localhost/enrol/lti/launch.php',
    'http://localhost/enrol/lti/launch_deeplink.php'
);