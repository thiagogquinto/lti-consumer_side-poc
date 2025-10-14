<?php

use OAT\Library\Lti1p3Core\Resource\LtiResourceLink\LtiResourceLink;

$ltiResourceLink = new LtiResourceLink(
    'resourceLinkIdentifier',
    [
        'url' => 'http://localhost/enrol/lti/launch.php',
        'title' => 'Some title'
    ]
);
