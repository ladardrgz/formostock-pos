<?php
class Sucursal {
    private $conn;
    private $table_name = "tb_sucursal";

    public $id;
    public $nombreSucursal;
    public $persona_juridica_id;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para insertar una sucursal
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombreSucursal, persona_juridica_id)
                  VALUES (:nombreSucursal, :persona_juridica_id)";

        $stmt = $this->conn->prepare($query);

        // Asignar valores
        $stmt->bindParam(':nombreSucursal', $this->nombreSucursal);
        $stmt->bindParam(':persona_juridica_id', $this->persona_juridica_id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
