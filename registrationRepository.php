<?php

require_once __DIR__ . '/vendor/autoload.php';

use OAT\Library\Lti1p3Core\Registration\RegistrationInterface;
use OAT\Library\Lti1p3Core\Registration\RegistrationRepositoryInterface;

$registrationRepository = new class implements RegistrationRepositoryInterface {
    private $registrations;

    public function __construct()
    {
        // Exemplo de como popular os registros. Idealmente, carregue de um arquivo/config/banco.
        require_once __DIR__ . '/registration.php'; // $registration deve ser definido neste arquivo
        $this->registrations = [
            $registration->getIdentifier() => $registration,
            // Adicione outros registros conforme necessário
        ];
    }

    public function find(string $identifier): ?RegistrationInterface {
        return $this->registrations[$identifier] ?? null;
    }

    public function findAll(): array {
        return array_values($this->registrations);
    }

    public function findByClientId(string $clientId): ?RegistrationInterface {
        foreach ($this->registrations as $registration) {
            if ($registration->getClientId() === $clientId) {
                return $registration;
            }
        }
        return null;
    }

    public function findByPlatformIssuer(string $issuer, ?string $clientId = null): ?RegistrationInterface {
        foreach ($this->registrations as $registration) {
            if (
                $registration->getPlatform()->getIdentifier() === $issuer &&
                ($clientId === null || $registration->getClientId() === $clientId)
            ) {
                return $registration;
            }
        }
        return null;
    }

    public function findByToolIssuer(string $issuer, ?string $clientId = null): ?RegistrationInterface {
        foreach ($this->registrations as $registration) {
            if (
                $registration->getTool()->getIdentifier() === $issuer &&
                ($clientId === null || $registration->getClientId() === $clientId)
            ) {
                return $registration;
            }
        }
        return null;
    }
};