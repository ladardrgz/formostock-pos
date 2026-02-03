<?php
class PersonaFisica {
    private $conn;
    private $table_name = "tb_personas_fisicas";

    public $id;
    public $nombres;
    public $apellidos;
    public $fechaNacimiento;
    public $sexo;
    public $detalle_documento_id;
    public $detalle_contacto_id;
    public $estado_persona_id = 1; // Estado "activo" por defecto

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para insertar persona física
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombres, apellidos, fechaNacimiento, sexo, detalle_documento_id, detalle_contacto_id, estado_persona_id)
                  VALUES (:nombres, :apellidos, :fechaNacimiento, :sexo, :detalle_documento_id, :detalle_contacto_id, :estado_persona_id)";

        $stmt = $this->conn->prepare($query);

        // Asignar valores
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':fechaNacimiento', $this->fechaNacimiento);
        $stmt->bindParam(':sexo', $this->sexo);
        $stmt->bindParam(':detalle_documento_id', $this->detalle_documento_id);
        $stmt->bindParam(':detalle_contacto_id', $this->detalle_contacto_id);
        $stmt->bindParam(':estado_persona_id', $this->estado_persona_id);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }
    
    // Método para insertar detalles de documentos
    public function insertarDetalleDocumento($tipoDocumentoId, $valorDocumento) {
        $query = "INSERT INTO tb_detalle_documento (tipo_documento_id, valorDocumento)
                  VALUES (:tipo_documento_id, :valorDocumento)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tipo_documento_id', $tipoDocumentoId);
        $stmt->bindParam(':valorDocumento', $valorDocumento);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Método para insertar detalles de contacto
    public function insertarDetalleContacto($tipoContactoId, $valorContacto) {
        $query = "INSERT INTO tb_detalle_contacto (tipo_contacto_id, valorDetalleContacto)
                  VALUES (:tipo_contacto_id, :valorDetalleContacto)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tipo_contacto_id', $tipoContactoId);
        $stmt->bindParam(':valorDetalleContacto', $valorContacto);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
}
?>
