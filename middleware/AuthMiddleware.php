<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    private static $secret_key = "tu_clave_secreta";

    public static function verifyToken($jwt) {
        try {
            // Decodificar usando la nueva firma de JWT::decode
            $decoded = JWT::decode($jwt, new Key(self::$secret_key, 'HS256'));
            
            // Aquí puedes retornar todo el objeto decodificado
            return $decoded;
        } catch (Exception $e) {
            // Si falla, retornamos null
            return null;
        }
    }

    public static function extractToken() {
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        foreach ($headers as $k => $v) {
            if (strtolower($k) === 'authorization') return $v;
        }
    }
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) return $_SERVER['HTTP_AUTHORIZATION'];
    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    if (function_exists('apache_request_headers')) {
        $arh = apache_request_headers();
        foreach ($arh as $k => $v) {
            if (strtolower($k) === 'authorization') return $v;
        }
    }
    return null;
}

}

