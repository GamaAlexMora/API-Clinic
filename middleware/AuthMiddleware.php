<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    public static function verifyToken($jwt) {
        if(!$jwt) {
            return null;
        }

        $secret_key = getenv('JWT_SECRET');
        if(!$secret_key) {
            return null;
        }

        try {
            return JWT::decode($jwt, new Key($secret_key, 'HS256'));
        } catch (Exception $e) {
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
            $headers = apache_request_headers();
            foreach ($headers as $k => $v) {
                if (strtolower($k) === 'authorization') return $v;
            }
        }

        return null;
    }
}
?>