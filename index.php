<?php
require_once 'config/database.php';
require_once 'middleware/AuthMiddleware.php';
require_once 'vendor/autoload.php';

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Obtener la URL solicitada y el método HTTP
$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Autenticación (excepto para el endpoint de login)
$auth = new AuthMiddleware();
$jwt = null;

// Excluir la ruta de login de la autenticación
if($request_uri == '/api_clinica/login' && $method == 'POST') {
    include_once 'controllers/AuthController.php';
    $authController = new AuthController();
    $authController->login();
    exit;
}

// Intenta primero con apache_request_headers()
$headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
if(isset($headers['Authorization'])) {
    $matches = [];
    preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches);
    if(isset($matches[1])) {
        $jwt = $matches[1];
    }
}

// Si no se encontró, prueba con $_SERVER
if(!$jwt && isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $matches = [];
    preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches);
    if(isset($matches[1])) {
        $jwt = $matches[1];
    }
}

// Verificar token para todas las demás rutas
$user_data = $auth->verifyToken($jwt);
if(!$user_data) {
    http_response_code(401);
    echo json_encode(array("message" => "Acceso denegado."));
    exit;
}

// Enrutamiento básico para pacientes y enfermedades
// Aquí puedes definir tus rutas y llamar a los controladores correspondientes
// Por ejemplo:
if($request_uri == '/api_clinica/pacientes' && $method == 'GET') {
    include_once 'controllers/PacienteController.php';
    $controller = new PacienteController();
    $controller->read();
} elseif($request_uri == '/api_clinica/pacientes' && $method == 'POST') {
    include_once 'controllers/PacienteController.php';
    $controller = new PacienteController();
    $controller->create();
} // ... y así para las demás rutas y métodos

if($request_uri == '/api_clinica/enfermedades' && $method == 'GET') {
    include_once 'controllers/EnfermedadesController.php';
    $controller = new EnfermedadController();
    $controller->read();
} elseif($request_uri == '/api_clinica/enfermedades' && $method == 'POST') {
    include_once 'controllers/EnfermedadesController.php';
    $controller = new EnfermedadController();
    $controller->create();
}

?>