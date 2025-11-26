<?php
class Paciente {
    private $conn;
    private $table_name = "pacientes";

    public $id;
    public $nombre;
    public $apellido;
    public $fecha_nacimiento;
    public $genero;
    public $telefono;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método CREATE (ya lo tienes)
    function create() {
        $query = "INSERT INTO " . $this->table_name . "
                 SET nombre=:nombre, apellido=:apellido, fecha_nacimiento=:fecha_nacimiento,
                     genero=:genero, telefono=:telefono, email=:email";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->fecha_nacimiento = htmlspecialchars(strip_tags($this->fecha_nacimiento));
        $this->genero = htmlspecialchars(strip_tags($this->genero));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":fecha_nacimiento", $this->fecha_nacimiento);
        $stmt->bindParam(":genero", $this->genero);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Método READ - Obtener todos los pacientes
    function read() {
        $query = "SELECT id, nombre, apellido, fecha_nacimiento, genero, telefono, email
                  FROM " . $this->table_name . "
                  ORDER BY apellido, nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método READ ONE - Obtener un solo paciente por ID
    function readOne() {
        $query = "SELECT id, nombre, apellido, fecha_nacimiento, genero, telefono, email
                  FROM " . $this->table_name . "
                  WHERE id = ?
                  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->fecha_nacimiento = $row['fecha_nacimiento'];
            $this->genero = $row['genero'];
            $this->telefono = $row['telefono'];
            $this->email = $row['email'];
            return true;
        }
        return false;
    }

    // Método UPDATE - Actualizar paciente
    function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET nombre = :nombre, apellido = :apellido, fecha_nacimiento = :fecha_nacimiento,
                      genero = :genero, telefono = :telefono, email = :email
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->fecha_nacimiento = htmlspecialchars(strip_tags($this->fecha_nacimiento));
        $this->genero = htmlspecialchars(strip_tags($this->genero));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":fecha_nacimiento", $this->fecha_nacimiento);
        $stmt->bindParam(":genero", $this->genero);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método DELETE - Eliminar paciente
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
       
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método SEARCH - Buscar pacientes
    function search($keywords) {
        $query = "SELECT id, nombre, apellido, fecha_nacimiento, genero, telefono, email
                  FROM " . $this->table_name . "
                  WHERE nombre LIKE ? OR apellido LIKE ? OR email LIKE ?
                  ORDER BY apellido, nombre";
        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        $stmt->execute();
        return $stmt;
    }

    // Método para verificar si email ya existe
    function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
       
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
?>
