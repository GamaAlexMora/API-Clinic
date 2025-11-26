<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../config/Database.php';

class EnfermedadController {
    private $db;
    private $conn;
    private $table = 'enfermedades'; // nombre de tu tabla
   
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }
   
    // Obtener todas las enfermedades
    public function getAll() {
        AuthMiddleware::verifyToken(AuthMiddleware::extractToken());
       
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
       
        $enfermedades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($enfermedades);
    }
   
    // Crear una nueva enfermedad
    public function create() {
        AuthMiddleware::verifyToken(AuthMiddleware::extractToken());

        $data = json_decode(file_get_contents("php://input"));
       
        $query = "INSERT INTO " . $this->table . "
                 (nombre_enfermedad, sintomas, tratamiento) 
                 VALUES (:nombre_enfermedad, :sintomas, :tratamiento)";
       
        $stmt = $this->conn->prepare($query);
       
        $stmt->bindParam(':nombre_enfermedad', $data->nombre_enfermedad);
        $stmt->bindParam(':sintomas', $data->sintomas);
        $stmt->bindParam(':tratamiento', $data->tratamiento);
       
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Enfermedad creada exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al crear enfermedad']);
        }
    }

    // Método read (para compatibilidad)
    public function read() {
        return $this->getAll();
    }
}
?>
