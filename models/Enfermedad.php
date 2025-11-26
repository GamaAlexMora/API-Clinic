<?php
class Enfermedad {
    private $conn;
    private $table_name = "enfermedades";

    public $id;
    public $Nombre_Enfermedad;
    public $Sintomas;
    public $Tratamiento;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método CREATE - Crear nueva enfermedad
    function create() {
        $query = "INSERT INTO " . $this->table_name . "
                 SET Nombre_Enfermedad=:Nombre_Enfermedad, Sintomas=:Sintomas, Tratamiento=:Tratamiento";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->Nombre_Enfermedad = htmlspecialchars(strip_tags($this->Nombre_Enfermedad));
        $this->Sintomas = htmlspecialchars(strip_tags($this->Sintomas));
        $this->Tratamiento = htmlspecialchars(strip_tags($this->Tratamiento));

        // Vincular valores
        $stmt->bindParam(":Nombre_Enfermedad", $this->Nombre_Enfermedad);
        $stmt->bindParam(":Sintomas", $this->Sintomas);
        $stmt->bindParam(":Tratamiento", $this->Tratamiento);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Método READ - Obtener todas las enfermedades
    function read() {
        $query = "SELECT id, Nombre_Enfermedad, Sintomas, Tratamiento
                  FROM " . $this->table_name . "
                  ORDER BY Nombre_Enfermedad";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método READ ONE - Obtener una sola enfermedad por ID
    function readOne() {
        $query = "SELECT id, Nombre_Enfermedad, Sintomas, Tratamiento
                  FROM " . $this->table_name . "
                  WHERE id = ?
                  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->Nombre_Enfermedad = $row['Nombre_Enfermedad'];
            $this->Sintomas = $row['Sintomas'];
            $this->Tratamiento = $row['Tratamiento'];
            return true;
        }
        return false;
    }

    // Método UPDATE - Actualizar enfermedad
    function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET Nombre_Enfermedad = :Nombre_Enfermedad,
                      Sintomas = :Sintomas,
                      Tratamiento = :Tratamiento
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->Nombre_Enfermedad = htmlspecialchars(strip_tags($this->Nombre_Enfermedad));
        $this->Sintomas = htmlspecialchars(strip_tags($this->Sintomas));
        $this->Tratamiento = htmlspecialchars(strip_tags($this->Tratamiento));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular valores
        $stmt->bindParam(":Nombre_Enfermedad", $this->Nombre_Enfermedad);
        $stmt->bindParam(":Sintomas", $this->Sintomas);
        $stmt->bindParam(":Tratamiento", $this->Tratamiento);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método DELETE - Eliminar enfermedad
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
}
?>