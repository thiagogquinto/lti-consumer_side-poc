<?php

use OAT\Library\Lti1p3Core\Registration\RegistrationRepositoryInterface;
use OAT\Library\Lti1p3Core\Security\Oidc\OidcAuthenticator;
use OAT\Library\Lti1p3Core\Security\User\UserAuthenticatorInterface;
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . '/../registrationRepository.php';
require_once __DIR__ . '/../userAuthenticator.php';

/** @var RegistrationRepositoryInterface $registrationRepository */
$registrationRepository = new RegistrationRepository();
$registration = $registrationRepository->find('registro-moodle-local-01');
echo $registration;

/** @var UserAuthenticatorInterface $userAuthenticator */
$userAuthenticator = new UserAuthenticator();

/** @var ServerRequestInterface $request */
// $request = 

// Create the OIDC authenticator
$authenticator = new OidcAuthenticator($registrationRepository, $userAuthenticator);

// Perform the login authentication (delegating to the $userAuthenticator with the hint 'loginHint')
$message = $authenticator->authenticate($request);

// Auto redirection to the tool via the user's browser
echo $message->toHtmlRedirectForm();