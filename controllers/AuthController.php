<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__. '/../vendor/autoload.php';

use Firebase\JWT\JWT;

class AuthController {
    private $db;
    private $table_name = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login() {
        if (!$this->db) {
            http_response_code(500);
            echo json_encode(array("message" => "Error de configuración de la base de datos."));
            return;
        }

        $data = json_decode(file_get_contents("php://input"));

        if(!isset($data->usuario) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(array("message" => "Faltan datos."));
            return;
        }

        $query = "SELECT id, usuario, password FROM " . $this->table_name . " WHERE usuario = ? LIMIT 0,1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $data->usuario);
        $stmt->execute();

        if($stmt->rowCount() === 0) {
            http_response_code(401);
            echo json_encode(array("message" => "Credenciales inválidas."));
            return;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!password_verify($data->password, $row['password'])) {
            http_response_code(401);
            echo json_encode(array("message" => "Credenciales inválidas."));
            return;
        }

        $secret_key = getenv('JWT_SECRET');
        if(!$secret_key) {
            http_response_code(500);
            echo json_encode(array("message" => "JWT_SECRET no está configurado."));
            return;
        }

        $issued_at = time();
        $token = array(
            "iss" => "api-clinic",
            "iat" => $issued_at,
            "nbf" => $issued_at,
            "exp" => $issued_at + 3600,
            "data" => array(
                "id" => $row['id'],
                "usuario" => $row['usuario']
            )
        );

        $jwt = JWT::encode($token, $secret_key, 'HS256');

        echo json_encode(array(
            "message" => "Login exitoso",
            "jwt" => $jwt
        ));
    }
}
?>