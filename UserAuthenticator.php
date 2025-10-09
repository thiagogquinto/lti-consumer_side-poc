<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Registration\RegistrationInterface;
use OAT\Library\Lti1p3Core\Security\User\UserAuthenticatorInterface;
use OAT\Library\Lti1p3Core\Security\User\Result\UserAuthenticationResult;
use OAT\Library\Lti1p3Core\Security\User\Result\UserAuthenticationResultInterface;
use OAT\Library\Lti1p3Core\User\UserIdentity;

class UserAuthenticator implements UserAuthenticatorInterface
{
   public function authenticate(
       RegistrationInterface $registration,
       string $loginHint
   ): UserAuthenticationResultInterface {
        return new UserAuthenticationResult(
           true,                                          // success
           new UserIdentity('userIdentifier', 'userName') // authenticated user identity
       );   
   }
}

// $userAuthenticator = new class implements UserAuthenticatorInterface
// {
//    public function authenticate(
//        RegistrationInterface $registration,
//        string $loginHint
//    ): UserAuthenticationResultInterface {
//         return new UserAuthenticationResult(
//            true,                                          // success
//            new UserIdentity('userIdentifier', 'userName') // authenticated user identity
//        );   
//    }
// };