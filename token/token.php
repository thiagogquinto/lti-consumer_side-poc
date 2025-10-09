<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../RegistrationRepository.php';
require_once __DIR__ . '/../utils.php';

use OAT\Library\Lti1p3Core\Registration\RegistrationRepositoryInterface;
use OAT\Library\Lti1p3Core\Security\Jwt\Builder\Builder;
use OAT\Library\Lti1p3Core\Security\Jwt\TokenInterface;
use OAT\Library\Lti1p3Core\Security\Jwt\Validator\Validator;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

header('Content-Type: application/json');
header('Cache-Control: no-store');
header('Pragma: no-cache');


try {
    // Verificar se é uma requisição POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new \Exception('Method not allowed', 405);
    }

    // Obter o repositório de registros
    $registrationRepository = getRegistrationRepository();

    // Criar request PSR-7
    $psr17Factory = new Psr17Factory();
    $request = $psr17Factory->createServerRequest(
        $_SERVER['REQUEST_METHOD'],
        $_SERVER['REQUEST_URI'] ?? '/',
        $_SERVER
    );

    if (!empty($_POST)) {
        $request = $request->withParsedBody($_POST);
    }

    $parsedBody = $request->getParsedBody();

    // Validar parâmetros obrigatórios OAuth2
    $grantType = $parsedBody['grant_type'] ?? null;
    $scope = $parsedBody['scope'] ?? null;
    $clientAssertion = $parsedBody['client_assertion'] ?? null;
    $clientAssertionType = $parsedBody['client_assertion_type'] ?? null;

    if ($grantType !== 'client_credentials') {
        throw new \Exception('Unsupported grant type', 400);
    }

    if ($clientAssertionType !== 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer') {
        throw new \Exception('Invalid client assertion type', 400);
    }

    if (empty($clientAssertion)) {
        throw new \Exception('Missing client assertion', 400);
    }

    if (empty($scope)) {
        throw new \Exception('Missing scope parameter', 400);
    }

    // Validar o escopo (deve incluir os serviços LTI necessários)
    $validScopes = [
        'https://purl.imsglobal.org/spec/lti-ags/scope/lineitem',
        'https://purl.imsglobal.org/spec/lti-ags/scope/lineitem.readonly',
        'https://purl.imsglobal.org/spec/lti-ags/scope/result.readonly',
        'https://purl.imsglobal.org/spec/lti-ags/scope/score',
        'https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly'
    ];

    $requestedScopes = explode(' ', $scope);
    $validRequestedScopes = array_intersect($requestedScopes, $validScopes);

    if (empty($validRequestedScopes)) {
        throw new \Exception('Invalid scope', 400);
    }

    // Validar o client assertion JWT
    $validator = new Validator();
    
    try {
        $token = $validator->load($clientAssertion);
    } catch (\Exception $e) {
        throw new \Exception('Invalid client assertion JWT: ' . $e->getMessage(), 400);
    }

    $claims = $token->getClaims();
    
    // Extrair client_id do JWT
    $clientId = $claims->get('sub') ?? $claims->get('iss');
    
    if (empty($clientId)) {
        throw new \Exception('Missing client_id in assertion', 400);
    }

    // Buscar o registro correspondente
    $registration = $registrationRepository->findByClientId($clientId);
    
    if ($registration === null) {
        throw new \Exception('Invalid client', 400);
    }

    // Validar a assinatura do JWT usando as chaves do tool (não da plataforma)
    $toolKeyChain = $registration->getToolKeyChain();
    if (!$toolKeyChain) {
        // Se não houver chave específica do tool, usar a chave da plataforma como fallback
        $toolKeyChain = $registration->getPlatformKeyChain();
    }
    
    if (!$toolKeyChain) {
        throw new \Exception('No keys available for validation', 500);
    }

    try {
        $isValid = $validator->verify($token, $toolKeyChain->getPublicKey());
        if (!$isValid) {
            throw new \Exception('Invalid JWT signature', 400);
        }
    } catch (\Exception $e) {
        throw new \Exception('JWT validation failed: ' . $e->getMessage(), 400);
    }

    // Validar claims do JWT
    $aud = $claims->get('aud');
    
    // Construir URL esperada do endpoint
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/token/token.php';
    $expectedAudience = $protocol . '://' . $host . $requestUri;
    
    // Audience alternativa baseada na plataforma
    $platformAudience = $registration->getPlatform()->getAudience() ?? $expectedAudience;
    
    if (!is_array($aud)) {
        $aud = [$aud];
    }
    
    // Verificar se alguma das audiences esperadas está presente
    $validAudiences = [$expectedAudience, $platformAudience];
    $audienceMatch = false;
    
    foreach ($validAudiences as $validAud) {
        if (in_array($validAud, $aud)) {
            $audienceMatch = true;
            break;
        }
    }
    
    if (!$audienceMatch) {
        throw new \Exception('Invalid audience in assertion. Expected one of: ' . implode(', ', $validAudiences) . '. Got: ' . implode(', ', $aud), 400);
    }

    // Verificar expiração
    $exp = $claims->get('exp');
    if ($exp && $exp < time()) {
        throw new \Exception('Client assertion expired', 400);
    }

    // Gerar access token
    $builder = new Builder();
    $platform = $registration->getPlatform();
    
    $accessTokenClaims = [
        'iss' => $platform->getIdentifier(),
        'sub' => $clientId,
        'aud' => $registration->getTool()->getIdentifier(),
        'iat' => time(),
        'exp' => time() + 3600, // Token válido por 1 hora
        'scope' => implode(' ', $validRequestedScopes),
        'token_type' => 'Bearer'
    ];

    $accessToken = $builder->build(
        $accessTokenClaims,
        $registration->getPlatformKeyChain()->getPrivateKey()
    );

    // Resposta OAuth2 padrão
    $response = [
        'access_token' => $accessToken->toString(),
        'token_type' => 'Bearer',
        'expires_in' => 3600,
        'scope' => implode(' ', $validRequestedScopes)
    ];

    http_response_code(200);
    echo json_encode($response);

} catch (\Exception $e) {
    // Tratamento de erros OAuth2
    $errorCode = $e->getCode();
    
    if ($errorCode === 405) {
        http_response_code(405);
        header('Allow: POST');
        $error = 'method_not_allowed';
        $errorDescription = 'Only POST method is allowed';
    } elseif ($errorCode === 400) {
        http_response_code(400);
        $error = 'invalid_request';
        $errorDescription = $e->getMessage();
    } else {
        http_response_code(500);
        $error = 'server_error';
        $errorDescription = 'Internal server error';
        error_log("Token endpoint error: " . $e->getMessage());
    }

    $errorResponse = [
        'error' => $error,
        'error_description' => $errorDescription
    ];

    echo json_encode($errorResponse);
}

