<?php
// Endpoint de autenticação OIDC/LTI
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/regRepo.php';

use OAT\Library\Lti1p3Core\Security\Oidc\OidcInitiator;

$issuer = $_GET['iss'] ?? null;
$clientId = $_GET['client_id'] ?? null;

if (!$issuer || !$clientId) {
	http_response_code(400);
	echo "Parâmetros obrigatórios ausentes.";
	exit;
}

$registration = $registrationRepository->findByClientId($clientId);
if (!$registration || $registration->getPlatform()->getIdentifier() !== $issuer) {
	http_response_code(401);
	echo "Registro não encontrado ou inválido.";
	exit;
}

$oidcInitiator = new OidcInitiator($registration);
$oidcInitiator->doOidcInitiationRedirect('http://localhost:9000/launch.php'); // URL de destino após autenticação
$oidcInitiator->doRedirect();
