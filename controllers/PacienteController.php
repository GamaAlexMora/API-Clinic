<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../config/Database.php';

class PacienteController {
    private $db;
    private $conn;
    private $table = 'pacientes';
   
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }
   
    public function getAll() {
        AuthMiddleware::verifyToken(AuthMiddleware::extractToken());
       
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
       
        $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($pacientes);
    }
   
    public function create() {
        $user = AuthMiddleware::verifyToken(AuthMiddleware::extractToken());

        $data = json_decode(file_get_contents("php://input"));
       
        $query = "INSERT INTO " . $this->table . "
                 (nombre, apellido, fecha_nacimiento, genero, telefono, email, direccion)
                 VALUES (:nombre, :apellido, :fecha_nacimiento, :genero, :telefono, :email, :direccion)";
       
        $stmt = $this->conn->prepare($query);
       
        $stmt->bindParam(':nombre', $data->nombre);
        $stmt->bindParam(':apellido', $data->apellido);
        $stmt->bindParam(':fecha_nacimiento', $data->fecha_nacimiento);
        $stmt->bindParam(':genero', $data->genero);
        $stmt->bindParam(':telefono', $data->telefono);
        $stmt->bindParam(':email', $data->email);
        $stmt->bindParam(':direccion', $data->direccion);
       
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Paciente creado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al crear paciente']);
        }
    }
   
    public function getPacienteEnfermedades($paciente_id) {
        AuthMiddleware::verifyToken(AuthMiddleware::extractToken());
       
        $query = "SELECT pe.*, e.nombre as enfermedad_nombre, e.descripcion
                  FROM paciente_enfermedad pe
                  JOIN enfermedades e ON pe.enfermedad_id = e.id
                  WHERE pe.paciente_id = :paciente_id";
       
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->execute();
       
        $enfermedades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($enfermedades);
    }

    public function read() {
        return $this->getAll(); // ← FIX CORRECTO
    }
}
?>