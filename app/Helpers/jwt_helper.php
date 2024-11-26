<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateJWT($data)
{
    $key = getenv('JWT_SECRET');

    if (empty($key)) {
        log_message('error', 'JWT_SECRET kosong atau tidak ditemukan.');
        throw new Exception('JWT_SECRET tidak ditemukan atau kosong.');
    }
    $payload = [
        'iss' => base_url(),
        'aud' => base_url(),
        'iat' => time(),
        // 'exp' => time() + 3600, // 1 hour expiration
        'data' => $data
    ];
    return JWT::encode($payload, $key, 'HS256');
}

function validateJWT($token)
{
    $key = getenv('JWT_SECRET');
    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));

        return (array) $decoded->data;

    } catch (Exception $e) {
        log_message('error', 'JWT Error: ' . $e->getMessage());
        return false;
    }
}
