<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/RegistrationRepository.php';

function getRegistrationRepository(): RegistrationRepository {
    require_once __DIR__ . '/registration.php';
    $registrationRepository = new RegistrationRepository($registration);
    return $registrationRepository;
}