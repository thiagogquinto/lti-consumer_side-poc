<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Registration\RegistrationInterface;
use OAT\Library\Lti1p3Core\Security\User\UserAuthenticatorInterface;
use OAT\Library\Lti1p3Core\Security\User\Result\UserAuthenticationResultInterface;

$userAuthenticator = new class implements UserAuthenticatorInterface
{
   public function authenticate(
       RegistrationInterface $registration,
       string $loginHint
   ): UserAuthenticationResultInterface {
       // Exemplo simples: autentica se loginHint não está vazio
       if (!empty($loginHint)) {
           return new class implements UserAuthenticationResultInterface {
               public function isAuthenticated(): bool { return true; }
               public function getUserId(): string { return 'user_' . uniqid(); }
               // Adicione outros métodos conforme necessário
           };
       }
       // Falha na autenticação
       return new class implements UserAuthenticationResultInterface {
           public function isAuthenticated(): bool { return false; }
           public function getUserId(): string { return ''; }
           // Adicione outros métodos conforme necessário
       };
   }
};