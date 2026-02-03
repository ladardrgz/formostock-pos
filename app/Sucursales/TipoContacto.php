<?php
class TipoContacto {
    private $conn;
    private $table_name = "tb_tipo_contacto";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para obtener todos los tipos de contacto
    public function obtenerTiposContacto() {
        $query = "SELECT idTipoContacto, nombreTipoContacto FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
