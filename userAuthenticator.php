<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Registration\RegistrationInterface;
use OAT\Library\Lti1p3Core\Security\User\UserAuthenticatorInterface;
use OAT\Library\Lti1p3Core\Security\User\Result\UserAuthenticationResult;
use OAT\Library\Lti1p3Core\Security\User\Result\UserAuthenticationResultInterface;
use OAT\Library\Lti1p3Core\User\UserIdentityInterface;

class SessionUserIdentity implements UserIdentityInterface
{
    private string $identifier;

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}

$userAuthenticator = new class implements UserAuthenticatorInterface
{
    public function authenticate(
        RegistrationInterface $registration,
        string $loginHint
    ): UserAuthenticationResultInterface {
        
        if (isset($_SESSION['user_id'])) {
            $userIdentity = new SessionUserIdentity($_SESSION['user_id']);

            return new UserAuthenticationResult(true, $userIdentity);
        }

        return new UserAuthenticationResult(false, null);
    }
};
