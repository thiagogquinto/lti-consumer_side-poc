<?php

session_start(); // Inicializar sessão para autenticação do usuário

use OAT\Library\Lti1p3Core\Registration\RegistrationRepositoryInterface;
use OAT\Library\Lti1p3Core\Security\Oidc\OidcAuthenticator;
// use OAT\Library\Lti1p3Core\Security\User\UserAuthenticatorInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\ServerRequest;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Interfaces/RegistrationRepository.php';
require_once __DIR__ . '/../Interfaces/UserAuthenticator.php';
require_once __DIR__ . '/../utils.php';

try {
    /** @var RegistrationRepositoryInterface $registrationRepository */
    $registrationRepository = getRegistrationRepository();
    $registration = $registrationRepository->find('registro-moodle-local-01');


    if ($registration === null) {
        throw new \Exception('Registration not found: registro-moodle-local-01');
    }

    $userAuthenticator = new UserAuthenticator();

    $psr17Factory = new Psr17Factory();

    $request = $psr17Factory->createServerRequest(
        $_SERVER['REQUEST_METHOD'] ?? 'GET',
        $_SERVER['REQUEST_URI'] ?? '/',
        $_SERVER
    );

    if (!empty($_GET)) {
        $request = $request->withQueryParams($_GET);
    }

    if (!empty($_POST)) {
        $request = $request->withParsedBody($_POST);
    }

    // Create the OIDC authenticator
    $authenticator = new OidcAuthenticator($registrationRepository, $userAuthenticator);

    // Perform the login authentication (delegating to the $userAuthenticator with the hint 'loginHint')
    $message = $authenticator->authenticate($request);

    // Auto redirection to the tool via the user's browser
    echo $message->toHtmlRedirectForm();
} catch (\Exception $e) {
    // Tratamento de erro básico
    http_response_code(400);
    echo "Error in OIDC authentication: " . htmlspecialchars($e->getMessage());
    error_log("OIDC Authentication Error: " . $e->getMessage());
}