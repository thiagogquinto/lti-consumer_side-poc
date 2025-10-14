<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/RegistrationRepository.php';
require_once __DIR__ . '/utils.php';

use OAT\Library\Lti1p3Core\Message\Launch\Builder\PlatformOriginatingLaunchBuilder;
use OAT\Library\Lti1p3Core\Message\Launch\Builder\LtiResourceLinkLaunchRequestBuilder;
use OAT\Library\Lti1p3Core\Message\LtiMessageInterface;
use OAT\Library\Lti1p3Core\Message\Payload\Claim\ContextClaim;
use OAT\Library\Lti1p3Core\Registration\RegistrationRepositoryInterface;
use OAT\Library\Lti1p3Core\Resource\LtiResourceLink\LtiResourceLink;

// Create a builder instance
// $builder = new PlatformOriginatingLaunchBuilder();
$builder = new LtiResourceLinkLaunchRequestBuilder();


$ltiResourceLink = new LtiResourceLink(
    'resourceLinkIdentifier',
    [
        'url' => 'http://localhost/enrol/lti/launch.php',
        'title' => 'Some title'
    ]
);

// Get related registration of the launch
$registrationRepository = getRegistrationRepository();
$registration = $registrationRepository->find('registro-moodle-local-01');
if ($registration === null) {
    throw new \Exception('Registration not found');
}

// Build a launch message
$message = $builder->buildLtiResourceLinkLaunchRequest(
    $ltiResourceLink,
    $registration,
    'loginHint',
    // LtiMessageInterface::LTI_MESSAGE_TYPE_RESOURCE_LINK_REQUEST,
    // 'http://localhost/enrol/lti/launch.php',
    null,
    [
        'http://purl.imsglobal.org/vocab/lis/v2/membership#Learner'
    ],
    [
        // 'id' => '045438e1-44ef-4cba-a315-d86623eaf734',
        // new ContextClaim('contextId')
        "https://purl.imsglobal.org/spec/lti/claim/custom" => ["id" => "045438e1-44ef-4cba-a315-d86623eaf734"]
    ]
);

echo $message->toHtmlRedirectForm();   // HTML hidden form, with possibility of auto redirection 