<?php
// Endpoint de access token (exemplo simples)
header('Content-Type: application/json');
echo json_encode([
    'access_token' => 'fake-token',
    'token_type' => 'Bearer',
    'expires_in' => 3600
]);
