<?php
class TipoDocumento {
    private $conn;
    private $table_name = "tb_tipo_documentos";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para obtener todos los tipos de documento
    public function obtenerTiposDocumento() {
        $query = "SELECT idTipoDocumento, nombreTipoDoc FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
