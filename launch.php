<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/registrationRepository.php';
require_once __DIR__ . '/utils.php';

use OAT\Library\Lti1p3Core\Message\Launch\Builder\PlatformOriginatingLaunchBuilder;
use OAT\Library\Lti1p3Core\Message\LtiMessageInterface;
use OAT\Library\Lti1p3Core\Message\Payload\Claim\ContextClaim;
use OAT\Library\Lti1p3Core\Registration\RegistrationRepositoryInterface;

// Create a builder instance
$builder = new PlatformOriginatingLaunchBuilder();

// Get related registration of the launch
$registrationRepository = getRegistrationRepository();
$registration = $registrationRepository->find('registro-moodle-local-01');
if ($registration === null) {
    throw new \Exception('Registration not found');
}

// Build a launch message
$message = $builder->buildPlatformOriginatingLaunch(
    $registration,
    LtiMessageInterface::LTI_MESSAGE_TYPE_RESOURCE_LINK_REQUEST,
    'http://localhost/moodle/enrol/lti/launch.php',
    'loginHint',
    null,
    [
        'http://purl.imsglobal.org/vocab/lis/v2/membership#Learner'
    ],
    [
        'myCustomClaim' => 'myCustomValue',
        new ContextClaim('contextIdentifier')
    ]
);


echo $message->toHtmlRedirectForm();   // HTML hidden form, with possibility of auto redirection 