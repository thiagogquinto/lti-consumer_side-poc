<?php
$publicKeyPath = '/home/thiago/Documentos/SINEPE/lti.pub'; // Caminho da chave pública

if (!file_exists($publicKeyPath)) {
    http_response_code(404);
    echo json_encode(['error' => 'Chave pública não encontrada']);
    exit;
}

$pem = file_get_contents($publicKeyPath);
$jwk = (new JWKConverter())->pemToJWK($pem);

$jwk['kty'] = 'RSA';
$jwk['kid'] = 'lti-key-id';
$jwk['use'] = 'sig';
$jwk['alg'] = 'RS256';
$jwk['e'] = 'AQAB'; // Exponente público padrão

header('Content-Type: application/json');
echo json_encode(['keys' => [$jwk]]);