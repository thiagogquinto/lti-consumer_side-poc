<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OAT\Library\Lti1p3Core\Registration\RegistrationInterface;
use OAT\Library\Lti1p3Core\Registration\RegistrationRepositoryInterface;
use \OAT\Library\Lti1p3Core\Registration\Registration;

class RegistrationRepository implements RegistrationRepositoryInterface {
    /** @var Registration[] */
    private array $registrations;

    public function __construct(Registration $initialRegistration)
    {
        $this->registrations = [
            $initialRegistration->getIdentifier() => $initialRegistration,
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