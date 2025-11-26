<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__. '/../vendor/autoload.php'; // Para cargar JWT

use Firebase\JWT\JWT;

class AuthController {
    private $db;
    private $table_name = "usuarios"; // Asumimos que tenemos una tabla de usuarios para autenticar

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login() {

        // Recibir datos POST (usuario y contraseña)
        $data = json_decode(file_get_contents("php://input"));

        // Validar que existan usuario y contraseña
        if(isset($data->usuario) && isset($data->password)) {
            // Consultar si el usuario existe (debes tener una tabla de usuarios)
            $query = "SELECT id, usuario, password FROM " . $this->table_name . " WHERE usuario = ? LIMIT 0,1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $data->usuario);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = $row['id'];
                $usuario = $row['usuario'];
                $password2 = $row['password'];

                // Verificar la contraseña (asumiendo que está hasheada)
                if(password_verify($data->password, $password2)) {
                    // Credenciales válidas, generar JWT
                    $secret_key = "tu_clave_secreta"; // Cambia por una clave segura
                    $issuer_claim = "localhost"; // Emisor
                    $audience_claim = "localhost"; // Audiencia
                    $issuedat_claim = time(); // Tiempo de emisión
                    $notbefore_claim = $issuedat_claim; // No antes de
                    $expire_claim = $issuedat_claim + 3600; // Expira en 1 hora

                    $token = array(
                        "iss" => $issuer_claim,
                        "aud" => $audience_claim,
                        "iat" => $issuedat_claim,
                        "nbf" => $notbefore_claim,
                        "exp" => $expire_claim,
                        "data" => array(
                            "id" => $id,
                            "usuario" => $usuario
                        ));

                    $jwt = JWT::encode($token, $secret_key, 'HS256');
                    echo json_encode(array(
                        "message" => "Login exitoso",
                        "jwt" => $jwt
                    ));
                } else {
                    echo json_encode(array("message" => "Login fallido."));
                }
            } else {
                echo json_encode(array("message" => "Usuario no encontrado."));
            }
        } else {
            echo json_encode(array("message" => "Faltan datos."));
        }
    }
}
?>